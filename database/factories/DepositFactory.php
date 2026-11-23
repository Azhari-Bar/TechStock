<?php

namespace Database\Factories;

use App\Models\Deposit;
use App\Models\Rental;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepositFactory extends Factory
{
    protected $model = Deposit::class;

    public function definition()
    {
        return [
            'rental_id' => Rental::inRandomOrder()->first()->id,
            'amount' => $this->faker->randomFloat(50, 200, 2),
            'status' => DepositStatus::Pending,
            'returned_amount' => 0,
            'deducted_amount' => 0,
            'returned_at' => null,
            'notes' => $this->faker->sentence(5),
        ];
    }
}