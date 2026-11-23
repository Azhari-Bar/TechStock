<?php

namespace App\Exports;

use App\Models\StockMovement;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StockMovementExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = StockMovement::with(['product', 'unit', 'supplier', 'user'])->latest();

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
            'Tanggal',
            'Jenis Pergerakan',
            'Produk',
            'Serial Unit',
            'Jumlah',
            'Status Sebelum',
            'Status Sesudah',
            'Supplier',
            'Harga Satuan',
            'Total',
            'Oleh',
            'Catatan',
            'Referensi',
        ];
    }

    public function map($movement): array
    {
        $ref = match ($movement->reference_type) {
            'rental' => 'Rental #'.($movement->reference_id ?? '-'),
            'product_unit' => 'Unit #'.($movement->reference_id ?? '-'),
            default => $movement->reference_type ?? '-',
        };

        return [
            optional($movement->created_at)->format('d-m-Y H:i'),
            $movement->movement_type?->label() ?? $movement->movement_type,
            $movement->product?->name ?? '-',
            $movement->unit?->serial_number ?? '-',
            $movement->quantity,
            $movement->status_before ?? '-',
            $movement->status_after ?? '-',
            $movement->supplier?->name ?? '-',
            $movement->unit_price ? 'Rp '.number_format((float) $movement->unit_price, 0, ',', '.') : '-',
            $movement->total_amount ? 'Rp '.number_format((float) $movement->total_amount, 0, ',', '.') : '-',
            $movement->user?->name ?? '-',
            $movement->notes ?? '-',
            $ref,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
