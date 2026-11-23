<?php

namespace App\Enums;

enum RentalStatus: string
{
    case Menunggu = 'menunggu';
    case Disetujui = 'disetujui';
    case Ditolak = 'ditolak';
    case Dipinjam = 'dipinjam';
    case MenungguPengembalian = 'menunggu_pengembalian';
    case Terlambat = 'terlambat';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu',
            self::Disetujui => 'Disetujui',
            self::Ditolak => 'Ditolak',
            self::Dipinjam => 'Dipinjam',
            self::MenungguPengembalian => 'Menunggu Pengembalian',
            self::Terlambat => 'Terlambat',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Menunggu => 'amber',
            self::Disetujui => 'blue',
            self::Ditolak => 'rose',
            self::Dipinjam => 'indigo',
            self::MenungguPengembalian => 'orange',
            self::Terlambat => 'red',
            self::Selesai => 'emerald',
            self::Dibatalkan => 'gray',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [self::Selesai, self::Ditolak, self::Dibatalkan]);
    }

    public function isActive(): bool
    {
        return in_array($this, [self::Disetujui, self::Dipinjam, self::MenungguPengembalian, self::Terlambat]);
    }
}
