<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductUnitFactory extends Factory
{
    protected $model = ProductUnit::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()?->id ?? Product::factory(),
            'serial_number' => 'SN-' . Str::random(8),
            'physical_condition' => 'baik',
            'grade' => 'baru',
            'acquisition_price' => $this->faker->numberBetween(500000, 10000000),
            'status' => $this->faker->randomElement(['available', 'reserved', 'sold', 'rented']),
        ];
    }
}