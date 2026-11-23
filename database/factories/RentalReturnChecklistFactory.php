<?php

namespace Database\Factories;

use App\Models\RentalReturnChecklist;
use App\Models\Rental;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalReturnChecklistFactory extends Factory
{
    protected $model = RentalReturnChecklist::class;

    public function definition()
    {
        return [
            'rental_id' => Rental::inRandomOrder()->first()->id,
            'product_unit_id' => ProductUnit::inRandomOrder()->first()->id,
            'condition_notes' => $this->faker->sentence(10),
            'damage_notes' => $this->faker->sentence(10),
            'photo_paths' => $this->faker->randomElements(
                [
                    'return1.jpg',
                    'return2.jpg',
                    'return3.jpg',
                ],
                2
            ),
            'checked_by' => User::inRandomOrder()->first()->id,
            'checked_at' => $this->faker->dateTimeBetween('now', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}