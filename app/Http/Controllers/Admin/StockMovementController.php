<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Rental;
use App\Models\StockMovement;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'unit', 'supplier', 'user'])->latest();

        if ($search = $request->string('q')->toString()) {
            $query->where(fn ($q) => $q->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                ->orWhereHas('unit', fn ($u) => $u->where('serial_number', 'like', "%{$search}%")));
        }

        if ($movementType = $request->string('type')->toString()) {
            $query->where('movement_type', $movementType);
        }

        if ($productId = $request->integer('product')) {
            $query->where('product_id', $productId);
        }

        $movements = $query->paginate(15)->withQueryString();

        $stats = [
            'stock_in' => StockMovement::where('movement_type', 'stock_in')->sum('quantity'),
            'stock_out' => StockMovement::where('movement_type', 'stock_out')->sum('quantity'),
            'rental' => StockMovement::where('movement_type', 'rental')->sum('quantity'),
            'return' => StockMovement::where('movement_type', 'rental_return')->sum('quantity'),
        ];

        $products = Product::orderBy('name')->get(['id', 'name']);
        $suppliers = Supplier::where('is_active', true)->get(['id', 'name']);

        return view('admin.stock-movements.index', compact('movements', 'stats', 'products', 'suppliers'));
    }

    public function create()
    {
        $products = Product::withCount([
            'units as available_units_count' => fn ($q) => $q->where('status', 'available'),
        ])->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('name')->get();

        return view('admin.stock-movements.create', compact('products', 'suppliers'));
    }

    public function store(Request $request)
    {
        // rental & rental_return TIDAK boleh lewat form ini — alur peminjaman punya jalur sendiri
        $data = $request->validate([
            'movement_type' => ['required', 'in:stock_in,stock_out,damaged,lost,adjustment'],
            'product_id' => ['required', 'exists:products,id'],
            'serial_numbers_text' => ['nullable', 'string'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'supplier_id' => ['nullable', 'exists:suppliers,id'],
            'unit_price' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $serials = collect(preg_split('/[\r\n,]+/', (string) ($data['serial_numbers_text'] ?? '')))
            ->map(fn ($s) => trim($s))
            ->filter()
            ->values()
            ->all();

        DB::transaction(function () use ($data, $serials) {
            $product = Product::findOrFail($data['product_id']);
            $type = $data['movement_type'];
            $notes = $data['notes'] ?? null;

            if ($type === 'stock_in') {
                $count = count($serials) > 0 ? count($serials) : (int) ($data['quantity'] ?? 1);

                for ($i = 0; $i < $count; $i++) {
                    $serial = $serials[$i] ?? 'SN-'.now()->format('ymd').'-'.strtoupper(substr(md5(uniqid('', true)), 0, 6));

                    // Cegah duplikasi serial
                    if (ProductUnit::where('product_id', $product->id)->where('serial_number', $serial)->exists()) {
                        continue;
                    }

                    $unit = ProductUnit::create([
                        'product_id' => $product->id,
                        'serial_number' => $serial,
                        'physical_condition' => 'baik',
                        'acquisition_price' => $data['unit_price'] ?? 0,
                        'status' => 'available',
                    ]);

                    $this->logMovement($product, $unit, $type, null, 'available', $data, $notes ?? 'Barang masuk dari supplier.');
                }

                return;
            }

            // Untuk type lain: update status unit yang sudah ada berdasarkan serial
            $units = count($serials) > 0
                ? ProductUnit::where('product_id', $product->id)->whereIn('serial_number', $serials)->get()
                : collect();

            // Cegah konflik: jangan ubah unit yang sedang dalam alur peminjaman aktif
            $conflictIds = [];
            if ($units->isNotEmpty()) {
                $conflictIds = Rental::whereIn('product_unit_id', $units->pluck('id')->all())
                    ->whereIn('status', ['disetujui', 'dipinjam', 'menunggu_pengembalian', 'terlambat'])
                    ->pluck('product_unit_id')
                    ->all();
            }

            $newStatus = match ($type) {
                'stock_out' => 'sold',
                'damaged' => 'damaged',
                'lost' => 'lost',
                default => null,
            };

            foreach ($units as $unit) {
                if (in_array($unit->id, $conflictIds, true)) {
                    continue; // lewati unit yang sedang dalam peminjaman aktif
                }

                $before = $unit->status->value;
                $after = $newStatus ?? $before;

                if ($after !== $before) {
                    $unit->update(['status' => $after]);
                }

                $this->logMovement($product, $unit, $type, $before, $after, $data, $notes ?? 'Pergerakan stok.');
            }

            if ($units->isEmpty()) {
                $this->logMovement($product, null, $type, null, $newStatus, $data, $notes ?? 'Pencatatan stok tanpa unit spesifik.');
            }
        });

        return redirect()->route('admin.stock-movements.index')
            ->with('success', 'Data pergerakan stok berhasil disimpan.');
    }

    public function show(StockMovement $movement)
    {
        $movement->load(['product', 'unit', 'supplier', 'user']);
        return view('admin.stock-movements.show', compact('movement'));
    }

    private function logMovement(Product $product, ?ProductUnit $unit, string $type, ?string $before, ?string $after, array $data, string $notes): void
    {
        $unitPrice = (float) ($data['unit_price'] ?? 0);

        StockMovement::create([
            'product_id' => $product->id,
            'product_unit_id' => $unit?->id,
            'supplier_id' => $data['supplier_id'] ?? null,
            'movement_type' => $type,
            'quantity' => 1,
            'reference_type' => 'product_unit',
            'reference_id' => $unit?->id,
            'status_before' => $before,
            'status_after' => $after,
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }
}
