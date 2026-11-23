<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductUnit;
use App\Models\Rental;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RentalFactory extends Factory
{
    protected $model = Rental::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('-1 month', '+1 week');
        $due = (clone $start)->modify('+'.$this->faker->numberBetween(1, 30).' days');

        return [
            'reference_number' => 'RNT-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
            'public_token_hash' => hash('sha256', Str::random(40)),
            'product_id' => Product::factory(),
            'product_unit_id' => null,
            'customer_name' => $this->faker->name(),
            'customer_phone' => $this->faker->numerify('08##########'),
            'customer_email' => $this->faker->safeEmail(),
            'customer_address' => $this->faker->streetAddress(),
            'purpose' => $this->faker->sentence(5),
            'identity_document_path' => 'rental-identities/placeholder.jpg',
            'start_date' => $start->format('Y-m-d'),
            'due_date' => $due->format('Y-m-d'),
            'rental_period_type' => 'daily',
            'rental_rate_snapshot' => 50000,
            'estimated_amount' => 150000,
            'deposit_amount' => 0,
            'status' => 'menunggu',
            'notes' => null,
        ];
    }
}
