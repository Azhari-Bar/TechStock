<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Payment as PaymentModel;
use App\Models\ProductUnit;
use App\Models\Rental;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentalAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with(['product', 'unit'])->latest();

        if ($search = $request->string('q')->toString()) {
            $query->where(fn ($q) => $q->where('reference_number', 'like', "%{$search}%")
                ->orWhere('customer_name', 'like', "%{$search}%")
                ->orWhere('customer_phone', 'like', "%{$search}%"));
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        $rentals = $query->paginate(15)->withQueryString();
        $statusCounts = Rental::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status')->toArray();
        $totalRentals = array_sum($statusCounts);

        return view('admin.rentals.index', compact('rentals', 'statusCounts', 'totalRentals'));
    }

    public function show(Rental $rental)
    {
        $rental->load(['product', 'unit', 'handoverChecklist', 'returnChecklist', 'extensions', 'deposits', 'fines', 'payments.verifier']);

        $availableUnits = ProductUnit::where('product_id', $rental->product_id)
            ->where('status', 'available')
            ->get();

        return view('admin.rentals.show', compact('rental', 'availableUnits'));
    }

    public function approve(Request $request, Rental $rental)
    {
        $data = $request->validate([
            'product_unit_id' => ['required', 'exists:product_units,id'],
            'deposit_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $error = DB::transaction(function () use ($rental, $data) {
            $rental = Rental::whereKey($rental->id)->lockForUpdate()->first();

            if ($rental->status->value !== 'menunggu') {
                return 'Status peminjaman tidak valid untuk disetujui (mungkin sudah diproses).';
            }

            $unit = ProductUnit::whereKey($data['product_unit_id'])
                ->where('product_id', $rental->product_id)
                ->where('status', 'available')
                ->lockForUpdate()
                ->first();

            if (! $unit) {
                return 'Unit tidak tersedia untuk produk ini.';
            }

            $unit->update(['status' => 'reserved']);
            $rental->update([
                'product_unit_id' => $unit->id,
                'deposit_amount' => $data['deposit_amount'],
                'status' => 'disetujui',
            ]);
            $rental->deposits()->create([
                'amount' => $data['deposit_amount'],
                'status' => 'pending',
            ]);

            $this->logMovement($rental, $unit, 'rental', 'available', 'reserved', 'Unit dialokasikan untuk peminjaman.');

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Peminjaman disetujui dan unit dialokasikan.');
    }

    public function handover(Request $request, Rental $rental)
    {
        $data = $request->validate([
            'condition_notes' => ['nullable', 'string'],
            'accessories' => ['nullable', 'array'],
            'accessories.*' => ['string', 'max:100'],
        ]);

        $error = DB::transaction(function () use ($rental, $data, $request) {
            $rental = Rental::whereKey($rental->id)->lockForUpdate()->first();

            if ($rental->status->value !== 'disetujui') {
                return 'Peminjaman belum disetujui atau sudah diproses.';
            }

            $unit = ProductUnit::whereKey($rental->product_unit_id)->lockForUpdate()->first();

            if (! $unit) {
                return 'Unit tidak ditemukan untuk serah terima.';
            }

            if ($unit->status->value !== 'reserved') {
                return 'Unit tidak dalam status dipesan (reserved).';
            }

            $unit->update(['status' => 'rented']);
            $rental->update(['status' => 'dipinjam']);
            $rental->handoverChecklist()->create([
                'product_unit_id' => $unit->id,
                'condition_notes' => $data['condition_notes'] ?? null,
                'accessories' => $data['accessories'] ?? [],
                'checked_by' => $request->user()->id,
                'checked_at' => now(),
            ]);

            $this->logMovement($rental, $unit, 'rental', 'reserved', 'rented', 'Serah terima unit ke penyewa.');

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Serah terima selesai. Unit kini berstatus dipinjam.');
    }

    public function returnUnit(Request $request, Rental $rental)
    {
        $data = $request->validate([
            'condition_notes' => ['nullable', 'string'],
            'damage_notes' => ['nullable', 'string'],
            'fine_amount' => ['nullable', 'numeric', 'min:0'],
            'fine_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $error = DB::transaction(function () use ($rental, $data, $request) {
            $rental = Rental::whereKey($rental->id)->lockForUpdate()->first();

            if (! in_array($rental->status->value, ['dipinjam', 'terlambat'], true)) {
                return 'Status peminjaman tidak valid untuk pengembalian.';
            }

            $unit = ProductUnit::whereKey($rental->product_unit_id)->lockForUpdate()->first();

            if (! $unit) {
                return 'Unit tidak ditemukan.';
            }

            if (! in_array($unit->status->value, ['rented', 'damaged', 'lost'], true)) {
                return 'Status unit tidak sesuai untuk pengembalian.';
            }

            $beforeUnit = $unit->status->value;

            $unit->update(['status' => 'available']);

            $rental->update([
                'status' => 'selesai',
                'returned_at' => now(),
            ]);

            $rental->returnChecklist()->create([
                'product_unit_id' => $unit->id,
                'condition_notes' => $data['condition_notes'] ?? null,
                'damage_notes' => $data['damage_notes'] ?? null,
                'checked_by' => $request->user()->id,
                'checked_at' => now(),
            ]);

            $fine = (float) ($data['fine_amount'] ?? 0);
            if ($fine > 0) {
                $rental->fines()->create([
                    'type' => 'damage_or_late',
                    'amount' => $fine,
                    'status' => 'unpaid',
                    'reason' => $data['fine_reason'] ?? 'Denda pengembalian / kerusakan',
                ]);
            }

            $deposit = $rental->deposits()->first();
            if ($deposit && $deposit->status->value === 'pending') {
                $deducted = min((float) $deposit->amount, $fine);
                $deposit->update([
                    'status' => $deducted > 0 ? 'deducted' : 'returned',
                    'returned_amount' => max(0, (float) $deposit->amount - $deducted),
                    'deducted_amount' => $deducted,
                    'returned_at' => now(),
                ]);
            }

            $this->logMovement($rental, $unit, 'rental_return', $beforeUnit, 'available', 'Pengembalian unit dari penyewa.');

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Unit berhasil dikembalikan dan peminjaman selesai.');
    }

    public function cancel(Rental $rental)
    {
        $error = DB::transaction(function () use ($rental) {
            $rental = Rental::whereKey($rental->id)->lockForUpdate()->first();

            if (! in_array($rental->status->value, ['menunggu', 'disetujui'], true)) {
                return 'Peminjaman tidak dapat dibatalkan pada status ini.';
            }

            $unit = $rental->product_unit_id
                ? ProductUnit::whereKey($rental->product_unit_id)->lockForUpdate()->first()
                : null;

            if ($unit && $unit->status->value === 'reserved') {
                $unit->update(['status' => 'available']);
                $this->logMovement($rental, $unit, 'rental', 'reserved', 'available', 'Peminjaman dibatalkan, unit dikembalikan ke stok.');
            }

            $rental->update(['status' => 'dibatalkan']);

            return null;
        });

        if ($error) {
            return back()->with('error', $error);
        }

        return back()->with('success', 'Peminjaman dibatalkan dan unit dikembalikan ke stok.');
    }

    public function verifyPayment(Request $request, Rental $rental, PaymentModel $payment)
    {
        abort_unless($rental->id === $payment->payable_id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:verified,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => $data['status'],
            'verified_by' => auth()->id(),
            'verified_at' => now(),
            'notes' => $data['notes'] ?? null,
        ]);

        return back()->with('success', 'Bukti pembayaran telah diverifikasi.');
    }

    public function identityDocument(Request $request, Rental $rental)
    {
        $rental = Rental::whereKey($rental->id)->first();
        $path = $rental->identity_document_path;

        if (!$path) {
            return back()->with('error', 'Tidak ada dokumen identitas.');
        }

        $fullPath = storage_path('app/rental-identities/' . $path);
        if (! file_exists($fullPath)) {
            return back()->with('error', 'Dokumen tidak ditemukan.');
        }

        return response()->file($fullPath);
    }

    private function logMovement(Rental $rental, ProductUnit $unit, string $type, ?string $before, ?string $after, string $notes): void
    {
        StockMovement::create([
            'product_id' => $rental->product_id,
            'product_unit_id' => $unit->id,
            'movement_type' => $type,
            'quantity' => 1,
            'reference_type' => 'rental',
            'reference_id' => $rental->id,
            'status_before' => $before,
            'status_after' => $after,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }
}