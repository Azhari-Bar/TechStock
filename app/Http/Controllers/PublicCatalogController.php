<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Http\Request;

class PublicCatalogController extends Controller
{
    public function index(Request $request)
    {
        $baseQuery = Product::with(['category', 'brand', 'units' => fn ($q) => $q->where('status', 'available')])
            ->where('is_active', true);

        if ($search = $request->string('q')->toString()) {
            $baseQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('processor', 'like', "%{$search}%");
            });
        }

        if ($category = $request->integer('category')) {
            $baseQuery->where('category_id', $category);
        }

        if ($condition = $request->string('condition')->toString()) {
            $baseQuery->where('condition', $condition);
        }

        // Filter lokasi gudang (berdasarkan unit yang tersedia di lokasi tersebut)
        if ($location = $request->string('location')->toString()) {
            $baseQuery->whereHas('units', function ($q) use ($location) {
                $q->where('status', 'available')
                    ->where(function ($locQ) use ($location) {
                        $locQ->where('room', 'like', "%{$location}%")
                             ->orWhere('rack', 'like', "%{$location}%")
                             ->orWhere('shelf', 'like', "%{$location}%");
                    });
            });
        }

        // Mode: all / borrow
        $filterMode = $request->get('mode', 'all');
        $filterQuery = $request->except(['page', 'mode']) + ['mode' => $filterMode];
        $countQuery = fn() => Product::where('is_active', true);
        $pageName = 'page';

        if ($filterMode === 'borrow') {
            $baseQuery->where('is_borrowable', true);
            $pageName = 'page_borrow';
        }

        $products = $baseQuery
            ->withCount(['units as available_units_count' => fn ($unitQuery) => $unitQuery->where('status', 'available')])
            ->latest()
            ->paginate(12, ['*'], $pageName)
            ->appends($filterQuery);

        // Total semua barang dan borrowable untuk header
        $totalAll = $countQuery()->count();
        $totalBorrow = $countQuery()->where('is_borrowable', true)->count();

        // Statistik inventaris
        $totalUnits = ProductUnit::count();
        $availableUnits = ProductUnit::where('status', 'available')->count();
        $rentedUnits = ProductUnit::where('status', 'rented')->count();
        $serviceUnits = ProductUnit::where('status', 'service')->count();
        $damagedUnits = ProductUnit::where('status', 'damaged')->count();

        // Produk per kategori
        $categoryStats = Product::withCount(['units as available_units' => fn($q) => $q->where('status', 'available')])
            ->select('categories.name as category_name', 'categories.slug', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('products.is_active', true)
            ->groupBy('categories.id', 'categories.name', 'categories.slug', 'products.id')
            ->get()
            ->groupBy('category_name')
            ->map(fn($items) => [
                'total' => $items->sum('id'),
                'available' => $items->sum('available_units')
            ])
            ->filter(fn($stats) => $stats['total'] > 0)
            ->take(3);

        // Daftar ruangan untuk filter
        $rooms = ProductUnit::where('status', 'available')
            ->whereNotNull('room')
            ->distinct()
            ->orderBy('room')
            ->pluck('room');

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('catalog.index', compact('products', 'categories', 'rooms', 'filterMode', 'totalAll', 'totalBorrow', 'totalUnits', 'availableUnits', 'rentedUnits', 'serviceUnits', 'damagedUnits', 'categoryStats'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);
        $product->load(['category', 'brand', 'units' => fn ($q) => $q->where('status', 'available')]);
        $product->units_count = $product->units()->where('status', 'available')->count();
        return view('catalog.show', compact('product'));
    }
}