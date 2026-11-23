<?php

namespace App\Enums;

enum DepositStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case Returned = 'returned';
    case Deducted = 'deducted';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Menunggu',
            self::Paid => 'Dibayar',
            self::Returned => 'Dikembalikan',
            self::Deducted => 'Dipotong',
        };
    }
}
