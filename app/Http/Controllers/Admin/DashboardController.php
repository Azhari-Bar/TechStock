<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Rental;
use App\Models\StockMovement;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $pendingRentals = Rental::whereIn('status', ['menunggu', 'disetujui'])->count();
        $activeRentals = Rental::whereIn('status', ['dipinjam', 'menunggu_pengembalian', 'terlambat'])->count();
        $completedRentals = Rental::where('status', 'selesai')->count();
        $overdueRentals = Rental::where('status', 'terlambat')->count();

        $unitCounts = ProductUnit::selectRaw('status, COUNT(*) as total')->groupBy('status')->pluck('total', 'status');
        $totalUnits = ProductUnit::count();
        $availableUnits = (int) ($unitCounts['available'] ?? 0);
        $rentedUnits = (int) ($unitCounts['rented'] ?? 0);
        $serviceUnits = (int) ($unitCounts['service'] ?? 0);
        $damagedUnits = (int) ($unitCounts['damaged'] ?? 0);
        $lostUnits = (int) ($unitCounts['lost'] ?? 0);
        $reservedUnits = (int) ($unitCounts['reserved'] ?? 0);

        $categoryStats = Product::query()
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->leftJoin('product_units', 'products.id', '=', 'product_units.product_id')
            ->selectRaw('categories.name as name, COUNT(DISTINCT products.id) as products_count, COUNT(product_units.id) as units_count')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('units_count')
            ->get();

        $monthlyRentals = collect(range(5, 0))->map(function ($offset) {
            $month = now()->startOfMonth()->subMonths($offset);
            return [
                'label' => $month->translatedFormat('M'),
                'total' => Rental::whereBetween('created_at', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()])->count(),
            ];
        });

        $recentMovements = StockMovement::with(['product', 'unit'])
            ->latest()
            ->take(6)
            ->get();

        $recentRentals = Rental::with('product')
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'activeProducts',
            'pendingRentals',
            'activeRentals',
            'completedRentals',
            'overdueRentals',
            'recentRentals',
            'totalUnits',
            'availableUnits',
            'rentedUnits',
            'serviceUnits',
            'damagedUnits',
            'lostUnits',
            'reservedUnits',
            'categoryStats',
            'monthlyRentals',
            'recentMovements'
        ));
    }
}
