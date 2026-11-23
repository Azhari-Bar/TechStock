<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Buat category jika belum ada
        $laptopCategory = Category::firstOrCreate(['slug' => 'test-laptop'], ['name' => 'Test Laptop', 'type' => 'laptop']);
        $desktopCategory = Category::firstOrCreate(['slug' => 'test-desktop'], ['name' => 'Test Desktop', 'type' => 'desktop']);
        $accessoryCategory = Category::firstOrCreate(['slug' => 'test-aksessoris'], ['name' => 'Test Aksesoris', 'type' => 'accessory']);

        // Buat brand jika belum ada
        $brand = Brand::firstOrCreate(['name' => 'TestBrand'], ['slug' => 'testbrand']);

        // ================================
        // TEST DATA 1-4: Laptop Borrowable (bisa dipinjam)
        // ================================
        $laptops = [
            ['name' => 'Laptop Test 1', 'condition' => 'new', 'is_borrowable' => true],
            ['name' => 'Laptop Test 2', 'condition' => 'used', 'is_borrowable' => true],
            ['name' => 'Laptop Test 3', 'condition' => 'new', 'is_borrowable' => true],
            ['name' => 'Laptop Test 4', 'condition' => 'used', 'is_borrowable' => true],
        ];

        foreach ($laptops as $i => $data) {
            $product = Product::create([
                'category_id' => $laptopCategory->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'model' => 'Model Test ' . ($i + 1),
                'condition' => $data['condition'],
                'is_borrowable' => $data['is_borrowable'],
                'is_active' => true,
                'rental_daily_rate' => 50000 + ($i * 10000),
            ]);
            $this->createUnits($product, 'LPT');
        }

        // ================================
        // TEST DATA 5-6: Laptop NOT borrowable (tidak bisa dipinjam)
        // ================================
        $nonBorrowable = [
            ['name' => 'Laptop Admin Only', 'condition' => 'new', 'is_borrowable' => false],
            ['name' => 'Laptop Service Unit', 'condition' => 'used', 'is_borrowable' => false],
        ];

        foreach ($nonBorrowable as $i => $data) {
            $idx = 4 + $i;
            $product = Product::create([
                'category_id' => $laptopCategory->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'model' => 'Model Non-Borrowable ' . ($i + 1),
                'condition' => $data['condition'],
                'is_borrowable' => false,
                'is_active' => true,
            ]);
            $this->createUnits($product, 'ADM');
        }

        // ================================
        // TEST DATA 7-8: Desktop Borrowable
        // ================================
        $desktops = [
            ['name' => 'Desktop Test 1', 'condition' => 'new', 'is_borrowable' => true],
            ['name' => 'Desktop Test 2', 'condition' => 'used', 'is_borrowable' => true],
        ];

        foreach ($desktops as $i => $data) {
            $idx = 6 + $i;
            $product = Product::create([
                'category_id' => $desktopCategory->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'model' => 'Desktop Model ' . ($i + 1),
                'condition' => $data['condition'],
                'is_borrowable' => $data['is_borrowable'],
                'is_active' => true,
            ]);
            $this->createUnits($product, 'DSK');
        }

        // ================================
        // TEST DATA 9-10: Aksesoris
        // ================================
        $accessories = [
            ['name' => 'Mouse Test USB', 'is_borrowable' => true, 'condition' => 'new'],
            ['name' => 'Keyboard Test Wireless', 'is_borrowable' => false, 'condition' => 'new'],
        ];

        foreach ($accessories as $i => $data) {
            $product = Product::create([
                'category_id' => $accessoryCategory->id,
                'brand_id' => $brand->id,
                'name' => $data['name'],
                'model' => 'Accessory ' . ($i + 1),
                'condition' => $data['condition'],
                'is_borrowable' => $data['is_borrowable'],
                'is_active' => true,
            ]);
            $this->createUnits($product, 'ACC');
        }

        $this->command->info('✅ 10 data test berhasil dibuat!');
    }

    private function createUnits(Product $product, string $prefix): void
    {
        for ($j = 0; $j < rand(2, 4); $j++) {
            $status = 'available';
            $rooms = ['Gudang Utama', 'Ruang Display'];
            $racks = ['Rak A', 'Rak B'];
            $shelves = ['Tingkat 1', 'Tingkat 2'];

            ProductUnit::create([
                'product_id' => $product->id,
                'serial_number' => $prefix.str_pad($product->id . '-' . $j, 8, '0', STR_PAD_LEFT),
                'room' => $rooms[array_rand($rooms)],
                'rack' => $racks[array_rand($racks)],
                'shelf' => $shelves[array_rand($shelves)],
                'bin' => 'Box ' . str_pad((string) rand(1, 20), 2, '0', STR_PAD_LEFT),
                'physical_condition' => 'baik',
                'status' => $status,
            ]);
        }
    }
}