<?php

namespace App\Exports;

use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductCatalogExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected ?Carbon $from;
    protected ?Carbon $to;

    public function __construct($from = null, $to = null)
    {
        $this->from = $from ? Carbon::parse($from)->startOfDay() : null;
        $this->to = $to ? Carbon::parse($to)->endOfDay() : null;
    }

    public function collection()
    {
        $query = Product::with(['category', 'brand'])->orderBy('name');

        if ($this->from) {
            $query->where('created_at', '>=', $this->from);
        }
        if ($this->to) {
            $query->where('created_at', '<=', $this->to);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Nama Produk',
            'Kategori',
            'Merek',
            'Model',
            'Kondisi',
            'Harga Beli',
            'Harga Jual',
            'Tarif Sewa Harian',
            'Tarif Sewa Mingguan',
            'Tarif Sewa Bulanan',
            'Stok Minimum',
            'Unit Tersedia',
            'Status Aktif',
            'Dapat Dipinjam',
            'Diperbarui',
        ];
    }

    public function map($product): array
    {
        $condition = $product->condition?->value ?? $product->condition;
        $conditionLabel = match ($condition) {
            'new' => 'Baru',
            'used' => 'Bekas',
            default => $condition ?? '-',
        };

        return [
            $product->name,
            $product->category?->name ?? '-',
            $product->brand?->name ?? '-',
            $product->model ?? '-',
            $conditionLabel,
            $product->purchase_price ?? 0,
            $product->selling_price ?? 0,
            $product->rental_daily_rate ?? 0,
            $product->rental_weekly_rate ?? 0,
            $product->rental_monthly_rate ?? 0,
            $product->minimum_stock ?? 0,
            $product->available_units_count ?? $product->units()->where('status', 'available')->count(),
            $product->is_active ? 'Aktif' : 'Nonaktif',
            $product->is_borrowable ? 'Ya' : 'Tidak',
            optional($product->updated_at)->format('d-m-Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}