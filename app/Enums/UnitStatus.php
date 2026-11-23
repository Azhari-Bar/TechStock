<?php

namespace App\Enums;

enum UnitStatus: string
{
    case Available = 'available';
    case Reserved = 'reserved';
    case Sold = 'sold';
    case Rented = 'rented';
    case Service = 'service';
    case Damaged = 'damaged';
    case Lost = 'lost';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Tersedia',
            self::Reserved => 'Dipesan untuk Peminjaman',
            self::Sold => 'Keluar / Terjual',
            self::Rented => 'Sedang Dipinjam',
            self::Service => 'Servis',
            self::Damaged => 'Rusak',
            self::Lost => 'Hilang',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'green',
            self::Reserved => 'yellow',
            self::Sold => 'gray',
            self::Rented => 'blue',
            self::Service => 'orange',
            self::Damaged => 'red',
            self::Lost => 'red',
        };
    }

    public function isRentable(): bool
    {
        return $this === self::Available;
    }
}