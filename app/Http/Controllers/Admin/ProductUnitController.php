<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductUnitController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductUnit::with('product.category', 'product.brand')->latest();

        if ($search = $request->string('q')->toString()) {
            $query->where(fn ($q) => $q->where('serial_number', 'like', "%{$search}%")
                ->orWhere('room', 'like', "%{$search}%")
                ->orWhere('rack', 'like', "%{$search}%")
                ->orWhere('shelf', 'like', "%{$search}%")
                ->orWhere('bin', 'like', "%{$search}%")
                ->orWhereHas('product', fn ($p) => $p->where('name', 'like', "%{$search}%")));
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($productId = $request->integer('product')) {
            $query->where('product_id', $productId);
        }

        $units = $query->paginate(20)->withQueryString();

        $statusCounts = ProductUnit::selectRaw('status, count(*) as c')
            ->groupBy('status')
            ->pluck('c', 'status')
            ->toArray();

        $stats = [
            'total' => ProductUnit::count(),
            'available' => $statusCounts['available'] ?? 0,
            'rented' => $statusCounts['rented'] ?? 0,
            'maintenance' => ($statusCounts['service'] ?? 0) + ($statusCounts['damaged'] ?? 0),
        ];

        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.product-units.index', compact('units', 'stats', 'statusCounts', 'products'));
    }

    public function create(Request $request)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);
        $selectedProduct = $request->integer('product');

        return view('admin.product-units.create', compact('products', 'selectedProduct'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('product-units', 'public');
        }

        $unit = ProductUnit::create($data);

        $this->logMovement($unit, null, $unit->status->value, 'Penambahan unit baru ke inventaris.');

        return redirect()->route('admin.product-units.show', $unit)
            ->with('success', 'Unit inventaris berhasil ditambahkan.');
    }

    public function show(ProductUnit $unit)
    {
        $unit->load('product.category', 'product.brand', 'rental');

        return view('admin.product-units.show', compact('unit'));
    }

    public function edit(ProductUnit $unit)
    {
        $products = Product::orderBy('name')->get(['id', 'name']);

        return view('admin.product-units.edit', compact('unit', 'products'));
    }

    public function update(Request $request, ProductUnit $unit)
    {
        $data = $this->validated($request, $unit);
        $before = $unit->status->value;

        if ($request->hasFile('image')) {
            if ($unit->image_path) {
                Storage::disk('public')->delete($unit->image_path);
            }
            $data['image_path'] = $request->file('image')->store('product-units', 'public');
        }

        $unit->update($data);

        if ($before !== $unit->status->value) {
            $this->logMovement($unit, $before, $unit->status->value, 'Perubahan status unit melalui admin.');
        }

        return redirect()->route('admin.product-units.show', $unit)
            ->with('success', 'Unit inventaris berhasil diperbarui.');
    }

    public function destroy(ProductUnit $unit)
    {
        $activeRental = $unit->rental()
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam', 'terlambat', 'menunggu_pengembalian'])
            ->exists();

        if ($activeRental) {
            return back()->with('error', 'Unit masih terhubung dengan peminjaman aktif, tidak dapat dihapus.');
        }

        if (in_array($unit->status->value, ['reserved', 'rented'], true)) {
            return back()->with('error', 'Unit sedang dipesan/dipinjam, tidak dapat dihapus.');
        }

        $unit->delete();

        return redirect()->route('admin.product-units.index')
            ->with('success', 'Unit inventaris berhasil dihapus.');
    }

    protected function validated(Request $request, ?ProductUnit $unit = null): array
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'serial_number' => ['required', 'string', 'max:100', Rule::unique('product_units', 'serial_number')->ignore($unit?->id)],
            'room' => ['nullable', 'string', 'max:100'],
            'rack' => ['nullable', 'string', 'max:100'],
            'shelf' => ['nullable', 'string', 'max:100'],
            'bin' => ['nullable', 'string', 'max:100'],
            'physical_condition' => ['required', 'string', 'max:100'],
            'grade' => ['nullable', 'string', 'max:20'],
            'acquisition_price' => ['nullable', 'numeric', 'min:0'],
            'received_at' => ['nullable', 'date'],
            'warranty_until' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', 'in:available,reserved,sold,rented,service,damaged,lost'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $data['acquisition_price'] = $data['acquisition_price'] ?? 0;
        unset($data['image']);

        return $data;
    }

    private function logMovement(ProductUnit $unit, ?string $before, string $after, string $notes): void
    {
        StockMovement::create([
            'product_id' => $unit->product_id,
            'product_unit_id' => $unit->id,
            'movement_type' => 'adjustment',
            'quantity' => 1,
            'reference_type' => 'product_unit',
            'reference_id' => $unit->id,
            'status_before' => $before,
            'status_after' => $after,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);
    }
}