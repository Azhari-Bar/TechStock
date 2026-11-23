<?php

namespace App\Exports;

use App\Models\Rental;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RentalReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
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
        $query = Rental::with(['product.category', 'product.brand'])->latest();

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
            'No Referensi',
            'Nama Pelanggan',
            'No. HP',
            'Email',
            'Alamat',
            'Produk',
            'Kategori',
            'Merek',
            'Periode',
            'Tanggal Mulai',
            'Tanggal Jatuh Tempo',
            'Tanggal Kembali',
            'Status',
            'Tarif (Snapshot)',
            'Estimasi Biaya',
            'Deposit',
            'Keperluan',
            'Catatan',
            'Dibuat Pada',
        ];
    }

    public function map($rental): array
    {
        $period = match ($rental->rental_period_type) {
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            default => $rental->rental_period_type ?? '-',
        };

        return [
            $rental->reference_number,
            $rental->customer_name,
            $rental->customer_phone,
            $rental->customer_email ?? '-',
            $rental->customer_address ?? '-',
            $rental->product?->name ?? '-',
            $rental->product?->category?->name ?? '-',
            $rental->product?->brand?->name ?? '-',
            $period,
            optional($rental->start_date)->format('d-m-Y'),
            optional($rental->due_date)->format('d-m-Y'),
            optional($rental->returned_at)->format('d-m-Y H:i'),
            $rental->status?->label() ?? (string) $rental->status,
            $rental->rental_rate_snapshot ?? 0,
            $rental->estimated_amount ?? 0,
            $rental->deposit_amount ?? 0,
            $rental->purpose ?? '-',
            $rental->notes ?? '-',
            optional($rental->created_at)->format('d-m-Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
