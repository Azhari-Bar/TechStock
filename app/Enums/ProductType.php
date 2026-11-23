<?php

namespace App\Enums;

enum ProductType: string
{
    case Laptop = 'laptop';
    case Desktop = 'desktop';
    case Accessory = 'accessory';

    public function label(): string
    {
        return match ($this) {
            self::Laptop => 'Laptop',
            self::Desktop => 'Komputer Desktop',
            self::Accessory => 'Aksesoris & Komponen',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Laptop => '📱',
            self::Desktop => '💻',
            self::Accessory => '⚙️',
        };
    }
}
