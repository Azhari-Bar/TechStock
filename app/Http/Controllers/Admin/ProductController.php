<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ProductCondition;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand'])->withCount([
            'units as available_units_count' => fn ($q) => $q->where('status', 'available'),
            'units as total_units_count',
        ]);

        if ($search = $request->string('q')->toString()) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('processor', 'like', "%{$search}%")
                  ->orWhere('component_type', 'like', "%{$search}%");
            });
        }

        if ($category = $request->integer('category')) {
            $query->where('category_id', $category);
        }

        if ($condition = $request->string('condition')->toString()) {
            $query->where('condition', $condition);
        }

        // Filter periode penambahan produk
        if ($period = $request->string('period')->toString()) {
            [$from, $to] = $this->periodRange($period);
            if ($from) {
                $query->whereBetween('created_at', [$from, $to]);
            }
        }

        $products = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = ProductCondition::cases();

        return view('admin.products.create', compact('categories', 'brands', 'conditions'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $data['slug'] = Str::slug($data['name']).'-'.Str::lower(Str::random(5));

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand', 'units' => fn ($q) => $q->latest()]);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $conditions = ProductCondition::cases();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'conditions'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    protected function periodRange(string $period): array
    {
        return match ($period) {
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            default => [null, null],
        };
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'name' => ['required', 'string', 'max:150'],
            'model' => ['nullable', 'string', 'max:150'],
            'condition' => ['required', 'in:new,used'],
            'processor' => ['nullable', 'string', 'max:150'],
            'ram_gb' => ['nullable', 'numeric', 'min:0'],
            'storage_capacity' => ['nullable', 'numeric', 'min:0'],
            'storage_type' => ['nullable', 'string', 'max:50'],
            'component_type' => ['nullable', 'string', 'max:50'],
            'capacity_gb' => ['nullable', 'string', 'max:50'],
            'connectivity' => ['nullable', 'string', 'max:50'],
            'compatibility' => ['nullable', 'string', 'max:255'],
            'gpu' => ['nullable', 'string', 'max:150'],
            'screen_size' => ['nullable', 'numeric', 'min:0'],
            'operating_system' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'rental_daily_rate' => ['nullable', 'numeric', 'min:0'],
            'rental_weekly_rate' => ['nullable', 'numeric', 'min:0'],
            'rental_monthly_rate' => ['nullable', 'numeric', 'min:0'],
            'warranty_period' => ['nullable', 'string', 'max:50'],
            'minimum_stock' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
            'is_borrowable' => ['sometimes', 'boolean'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_borrowable'] = $request->boolean('is_borrowable');
        unset($data['image']);

        return $data;
    }
}
