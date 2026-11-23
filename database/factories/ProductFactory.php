<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $image = 'images/laptop.svg';
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'brand_id' => Brand::inRandomOrder()->first()?->id ?? Brand::factory(),
            'name' => $this->faker->word,
            'model' => $this->faker->word,
            'condition' => $this->faker->randomElement(['new', 'used']),
            'selling_price' => $this->faker->numberBetween(1000000, 50000000),
            'purchase_price' => $this->faker->numberBetween(800000, 40000000),
            'rental_daily_rate' => $this->faker->numberBetween(50000, 500000),
            'minimum_stock' => 5,
            'is_active' => true,
            'is_borrowable' => true,
            'image_path' => $image,
        ];
    }
}