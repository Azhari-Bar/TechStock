<?php

namespace Database\Factories;

use App\Models\Fine;
use App\Models\Rental;
use App\Models\ProductUnit;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FineFactory extends Factory
{
    protected $model = Fine::class;

    public function definition()
    {
        return [
            'rental_id' => Rental::inRandomOrder()->first()->id,
            'type' => 'late_return',
            'amount' => $this->faker->randomFloat(10, 50, 2),
            'paid_amount' => 0,
            'status' => FineStatus::Unpaid,
            'reason' => 'Terlambat kembalikan',
            'paid_at' => null,
        ];
    }
}