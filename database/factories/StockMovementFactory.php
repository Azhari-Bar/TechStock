<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Purchase;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockMovementFactory extends Factory
{
    protected $model = StockMovement::class;

    public function definition()
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'product_unit_id' => ProductUnit::inRandomOrder()->first()->id,
            'movement_type' => 'purchase',
            'quantity' => 1,
            'reference_type' => 'purchase',
            'reference_id' => Purchase::inRandomOrder()->first()->id,
            'status_before' => 'available',
            'status_after' => 'sold',
            'notes' => 'Sample stock movement',
            'created_by' => null,
        ];
    }
}