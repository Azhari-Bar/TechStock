<?php

namespace App\Enums;

enum PaymentType: string
{
    case Sales = 'sales';
    case Rental = 'rental';
    case Deposit = 'deposit';
    case Fine = 'fine';

    public function label(): string
    {
        return match ($this) {
            self::Sales => 'Penjualan',
            self::Rental => 'Peminjaman',
            self::Deposit => 'Deposit',
            self::Fine => 'Denda',
        };
    }
}
