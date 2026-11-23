<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RentalController extends Controller
{
    public function create(Request $request): View
    {
        $product = \App\Models\Product::whereKey($request->query('product'))->where('is_active', true)->where('is_borrowable', true)->firstOrFail();

        // Check if there's at least one available unit in warehouse stock
        $hasAvailableUnit = \App\Models\ProductUnit::where('product_id', $product->id)
            ->where('status', 'available')
            ->exists();

        abort_unless($hasAvailableUnit, 404, 'Tidak ada unit inventaris tersedia untuk peminjaman ini.');

        // External loan percentage (default 10%)
        $externalLoanPercentage = config('services.external_loan_percentage', 10);

        return view('rentals.create', compact('product', 'externalLoanPercentage'));
    }

    public function show(string $reference): View
    {
        $token = request()->query('token');
        abort_unless($token, 404);

        $rental = Rental::where('reference_number', $reference)
            ->where('public_token_hash', hash('sha256', (string) $token))
            ->with('product')
            ->firstOrFail();

        return view('rentals.show', compact('rental'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_address' => ['nullable', 'string', 'max:1000'],
            'purpose' => ['nullable', 'string', 'max:2000'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'due_date' => ['required', 'date', 'after:start_date'],
            'rental_period_type' => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'loan_type' => ['required', Rule::in(['internal', 'external'])],
            'identity_document' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        $product = \App\Models\Product::whereKey($data['product_id'])->where('is_active', true)->where('is_borrowable', true)->firstOrFail();
        
        $loanType = $data['loan_type'];
        $isExternal = $loanType === 'external';
        $purchasePrice = (float)($data['purchase_price'] ?? $product->purchase_price ?? 0);
        $externalPercentage = config('services.external_loan_percentage', 10);
        
        // Calculate rate based on loan type
        if ($isExternal) {
            // For external loans, use purchase price as base
            $rate = match ($data['rental_period_type']) {
                'daily' => $purchasePrice * ($externalPercentage / 100) / 30, // Daily rate from monthly percentage
                'weekly' => $purchasePrice * ($externalPercentage / 100) / 7,
                'monthly' => $purchasePrice * ($externalPercentage / 100),
            };
        } else {
            // For internal loans, use product rates
            $rate = match ($data['rental_period_type']) {
                'daily' => $product->rental_daily_rate,
                'weekly' => $product->rental_weekly_rate,
                'monthly' => $product->rental_monthly_rate,
            };
        }
        
        if (! $rate || $rate <= 0) {
            if ($isExternal && $purchasePrice > 0) {
                $rate = $purchasePrice * ($externalPercentage / 100) / 30;
            } else {
                throw ValidationException::withMessages(['rental_period_type' => 'Tarif periode tersebut tidak tersedia.']);
            }
        }
        
        $documentPath = $request->file('identity_document')->store('rental-identities', 'local');
        $token = Str::random(64);
        try {
            $rental = Rental::create([
                ...collect($data)->except(['identity_document', 'product_id', 'purchase_price'])->all(),
                'reference_number' => 'RNT-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                'public_token_hash' => hash('sha256', $token),
                'product_id' => $product->id,
                'identity_document_path' => $documentPath,
                'rental_rate_snapshot' => $rate,
                'estimated_amount' => $this->estimateAmount($data['start_date'], $data['due_date'], (float) $rate, $data['rental_period_type']),
                'loan_type' => $loanType,
                'status' => 'menunggu',
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($documentPath);
            throw $exception;
        }
        return redirect()->route('rentals.show', [
            'reference' => $rental->reference_number,
            'token' => $token,
        ]);
    }

    public function success(Request $request): View
    {
        $rental = Rental::where('reference_number', $request->query('rental'))->where('public_token_hash', hash('sha256', (string) $request->query('token')))->with('product')->firstOrFail();
        return view('rentals.success', compact('rental'));
    }

    public function proofForm(Request $request, Rental $rental): View
    {
        $this->authorizePublicToken($request, $rental);
        return view('rentals.payment', compact('rental'));
    }

    public function submitProof(Request $request, Rental $rental): RedirectResponse
    {
        $this->authorizePublicToken($request, $rental);
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
            'payment_method' => ['required', Rule::in(['transfer', 'cash', 'qris', 'other'])],
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);
        abort_unless(! in_array($rental->status->value, ['selesai', 'dibatalkan', 'ditolak'], true), 422, 'Peminjaman sudah selesai atau dibatalkan.');

        $pendingPayments = $rental->payments()->where('status', 'pending')->exists();
        abort_if($pendingPayments, 422, 'Masih ada bukti pembayaran yang menunggu verifikasi.');

        if ($data['amount'] > ((float) $rental->estimated_amount + (float) $rental->deposit_amount)) {
            throw ValidationException::withMessages(['amount' => 'Jumlah pembayaran melebihi total estimasi dan deposit.']);
        }

        $path = $request->file('proof')->store('payment-proofs', 'local');
        try {
            $rental->payments()->create(['payment_type' => 'rental', 'amount' => $data['amount'], 'payment_method' => $data['payment_method'], 'proof_path' => $path, 'status' => 'pending', 'notes' => $data['notes'] ?? null]);
        } catch (\Throwable $exception) {
            Storage::disk('local')->delete($path);
            throw $exception;
        }
        return redirect()->route('rentals.payment.form', ['rental' => $rental, 'token' => $request->query('token')])->with('success', 'Bukti pembayaran terkirim untuk verifikasi.');
    }

    private function authorizePublicToken(Request $request, Rental $rental): void
    {
        abort_unless(hash_equals($rental->public_token_hash, hash('sha256', (string) $request->query('token'))), 404);
    }

    private function estimateAmount(string $start, string $due, float $rate, string $period): float
    {
        $days = max(1, \Carbon\Carbon::parse($start)->diffInDays(\Carbon\Carbon::parse($due)));
        $units = match ($period) { 'weekly' => (int) ceil($days / 7), 'monthly' => (int) ceil($days / 30), default => $days };
        return $units * $rate;
    }
}
