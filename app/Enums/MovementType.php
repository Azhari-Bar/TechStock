<?php

namespace App\Enums;

enum MovementType: string
{
    case StockIn = 'stock_in';
    case StockOut = 'stock_out';
    case Sale = 'sale';
    case Rental = 'rental';
    case RentalReturn = 'rental_return';
    case Adjustment = 'adjustment';
    case Damaged = 'damaged';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::StockIn => 'Barang Masuk',
            self::StockOut => 'Barang Keluar',
            self::Sale => 'Penjualan',
            self::Rental => 'Peminjaman',
            self::RentalReturn => 'Pengembalian',
            self::Adjustment => 'Penyesuaian',
            self::Damaged => 'Rusak',
            self::Lost => 'Hilang',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::StockIn => 'emerald',
            self::StockOut => 'amber',
            self::Sale => 'blue',
            self::Rental => 'indigo',
            self::RentalReturn => 'sky',
            self::Adjustment => 'slate',
            self::Damaged => 'red',
            self::Lost => 'red',
        };
    }
}
