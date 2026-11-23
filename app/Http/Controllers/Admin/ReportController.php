<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductCatalogExport;
use App\Exports\ProductUnitExport;
use App\Exports\RentalReportExport;
use App\Exports\StockMovementExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Rental;
use App\Models\StockMovement;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Rentang tanggal untuk filter periode: '' (semua), year, month, week.
     * $date opsional (default hari ini) supaya bisa dipakai untuk periode lampau.
     */
    private function range(?string $period, ?string $date = null): array
    {
        $d = $date ? Carbon::parse($date) : now();

        return match ($period) {
            'year' => [$d->copy()->startOfYear(), $d->copy()->endOfYear()],
            'month' => [$d->copy()->startOfMonth(), $d->copy()->endOfMonth()],
            'week' => [$d->copy()->startOfWeek(), $d->copy()->endOfWeek()],
            default => [null, null],
        };
    }

    private function suffix(?string $period, $from): string
    {
        return match ($period) {
            'year' => '-tahun-'.$from->year,
            'month' => '-bulan-'.$from->format('Y-m'),
            'week' => '-minggu-'.$from->format('o-\WW'),
            default => '',
        };
    }

    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_rentals' => Rental::count(),
            'pending_rentals' => Rental::whereIn('status', ['menunggu', 'disetujui'])->count(),
            'active_rentals' => Rental::whereIn('status', ['dipinjam', 'menunggu_pengembalian', 'terlambat'])->count(),
            'completed_rentals' => Rental::where('status', 'selesai')->count(),
            'total_stock_in' => StockMovement::where('movement_type', 'stock_in')->sum('quantity'),
            'total_stock_out' => StockMovement::where('movement_type', 'stock_out')->sum('quantity'),
            'total_movements' => StockMovement::count(),
        ];

        $recentRentals = Rental::with('product')
            ->latest()
            ->take(8)
            ->get();

        $recentMovements = StockMovement::with('product', 'unit')
            ->latest()
            ->take(6)
            ->get();

        return view('reports.index', compact('stats', 'recentRentals', 'recentMovements'));
    }

    public function exportRentals(?string $period = null, ?string $date = null)
    {
        [$from, $to] = $this->range($period, $date);

        return Excel::download(
            new RentalReportExport($from, $to),
            'laporan-peminjaman'.$this->suffix($period, $from).'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportProducts(?string $period = null, ?string $date = null)
    {
        [$from, $to] = $this->range($period, $date);

        return Excel::download(
            new ProductCatalogExport($from, $to),
            'laporan-katalog-produk'.$this->suffix($period, $from).'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportUnits(?string $period = null, ?string $date = null)
    {
        [$from, $to] = $this->range($period, $date);

        return Excel::download(
            new ProductUnitExport($from, $to),
            'laporan-unit-inventaris'.$this->suffix($period, $from).'-'.now()->format('Ymd-His').'.xlsx'
        );
    }

    public function exportStockMovements(?string $period = null, ?string $date = null)
    {
        [$from, $to] = $this->range($period, $date);

        return Excel::download(
            new StockMovementExport($from, $to),
            'laporan-pergerakan-stok'.$this->suffix($period, $from).'-'.now()->format('Ymd-His').'.xlsx'
        );
    }
}
