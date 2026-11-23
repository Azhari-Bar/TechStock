<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ProductUnit;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition()
    {
        return [
            'product_unit_id' => ProductUnit::inRandomOrder()->first()->id,
            'complaint' => $this->faker->sentence(15),
            'damage_description' => $this->faker->sentence(20),
            'technician' => $this->faker->name,
            'service_cost' => $this->faker->randomFloat(50, 500, 2),
            'service_date' => $this->faker->dateTimeBetween('now', 'now')->format('Y-m-d'),
            'completed_at' => null,
            'repair_action' => $this->faker->sentence(10),
            'status' => ServiceStatus::InProgress,
            'notes' => $this->faker->sentence(10),
        ];
    }
}