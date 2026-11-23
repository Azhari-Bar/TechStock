<?php

namespace Database\Factories;

use App\Models\Rental;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RentalHandoverChecklistFactory extends Factory
{
    protected $model = RentalHandoverChecklist::class;

    public function definition()
    {
        return [
            'rental_id' => Rental::inRandomOrder()->first()->id,
            'product_unit_id' => ProductUnit::inRandomOrder()->first()->id,
            'condition_notes' => $this->faker->sentence(10),
            'accessories' => $this->faker->randomElements(
                [
                    'charger',
                    'baterai',
                    'kabel',
                    'mouse',
                    'keyboard',
                    'case',
                ],
                2
            ),
            'photo_paths' => $this->faker->randomElements(
                [
                    'handover1.jpg',
                    'handover2.jpg',
                    'handover3.jpg',
                ],
                2
            ),
            'checked_by' => User::inRandomOrder()->first()->id,
            'checked_at' => $this->faker->dateTimeBetween('now', 'now')->format('Y-m-d H:i:s'),
        ];
    }
}