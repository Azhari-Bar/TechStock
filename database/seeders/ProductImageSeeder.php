<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        Product::withTrashed()->with('category')->chunkById(100, function ($products): void {
            foreach ($products as $product) {
                $type = $product->category?->type?->value;
                $image = match ($type) {
                    'desktop' => 'images/desktop.svg',
                    'accessory' => 'images/accessory.svg',
                    default => 'images/laptop.svg',
                };

                $product->forceFill(['image_path' => $image])->save();
            }
        });
    }
}