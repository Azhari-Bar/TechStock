<?php

namespace App\Exports;

use App\Models\ProductUnit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductUnitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = ProductUnit::with(['product.category', 'product.brand'])
            ->latest();

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
            'Serial Number',
            'Produk',
            'Kategori',
            'Merek',
            'Ruangan',
            'Rak',
            'Tingkat',
            'Kotak/Posisi',
            'Kondisi Fisik',
            'Harga Beli',
            'Status',
            'Catatan',
            'Dibuat Pada',
            'Diperbarui',
        ];
    }

    public function map($unit): array
    {
        return [
            $unit->serial_number ?? '-',
            $unit->product?->name ?? '-',
            $unit->product?->category?->name ?? '-',
            $unit->product?->brand?->name ?? '-',
            $unit->room ?? '—',
            $unit->rack ?? '—',
            $unit->shelf ?? '—',
            $unit->bin ?? '—',
            $unit->physical_condition ?? 'baik',
            $unit->acquisition_price ?? 0,
            $unit->status?->label() ?? (string)$unit->status,
            $unit->notes ?? '-',
            optional($unit->created_at)->format('d-m-Y H:i'),
            optional($unit->updated_at)->format('d-m-Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}