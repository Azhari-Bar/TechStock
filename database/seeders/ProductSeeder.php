<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::exists()) {
            return;
        }

        // Categories
        $laptopCategory = Category::firstOrCreate(
            ['slug' => 'laptop'],
            ['name' => 'Laptop', 'type' => 'laptop']
        );

        $desktopCategory = Category::firstOrCreate(
            ['slug' => 'desktop'],
            ['name' => 'Komputer Desktop', 'type' => 'desktop']
        );

        $accessoryCategory = Category::firstOrCreate(
            ['slug' => 'aksesoris-komponen'],
            ['name' => 'Aksesoris & Komponen', 'type' => 'accessory']
        );

        // Brands
        $brands = ['Dell', 'HP', 'Lenovo', 'Asus', 'Apple', 'Microsoft', 'Acer', 'MSI', 'Logitech', 'Samsung', 'Kingston', 'Western Digital'];
        foreach ($brands as $brandName) {
            Brand::firstOrCreate(
                ['name' => $brandName],
                ['name' => $brandName, 'slug' => Str::slug($brandName)]
            );
        }

        $this->seedLaptopsAndDesktops($laptopCategory, $desktopCategory);
        $this->seedAccessories($accessoryCategory);
    }

    private function seedLaptopsAndDesktops(Category $laptopCategory, Category $desktopCategory): void
    {
        $productNames = [
            'Laptop Gaming G15',
            'Ultrabook Slim 14',
            'Laptop Bisnis ThinkPad',
            'Desktop Gaming Tower',
            'Desktop Office Compact',
            'All-in-One Desktop Pro',
            'Laptop Workstation',
            'Gaming Laptop RTX 3060',
            'Laptop Pro 16',
            'Desktop Mini PC',
            'Laptop Creator 15',
            'Desktop Workstation',
        ];

        $processors = ['Intel i7-12700H', 'Intel i5-12500H', 'AMD Ryzen 7 7840U', 'Intel i7-13620H', 'AMD Ryzen 5 7640U'];

        for ($i = 0; $i < 12; $i++) {
            $category = ($i % 2 === 0) ? $laptopCategory : $desktopCategory;
            $brand = Brand::inRandomOrder()->first();
            $isLaptop = $category->type->value === 'laptop';

            $product = Product::create([
                'category_id' => $category->id,
                'brand_id' => $brand?->id,
                'name' => $productNames[$i].' '.($brand->name ?? 'Model'),
                'model' => 'Model '.($i + 1),
                'condition' => $i < 8 ? 'new' : 'used',
                'processor' => $processors[array_rand($processors)],
                'ram_gb' => rand(8, 32),
                'storage_capacity' => rand(512, 2000),
                'storage_type' => $isLaptop ? 'SSD' : 'HDD/SSD',
                'gpu' => $isLaptop ? ($i < 6 ? 'NVIDIA RTX 3060' : 'NVIDIA RTX 4060') : 'AMD Radeon RX 6600',
                'screen_size' => $isLaptop ? round((13 + $i * 2) * 10) / 10 : 24,
                'operating_system' => $isLaptop ? 'Windows 11 Home' : 'Windows 11 Pro',
                'description' => 'Produk berkualitas untuk kebutuhan '.($isLaptop ? 'portable' : 'desktop'),
                'purchase_price' => rand(8000000, 45000000),
                'selling_price' => rand(12000000, 55000000),
                'rental_daily_rate' => $isLaptop ? rand(50000, 800000) : rand(30000, 500000),
                'rental_weekly_rate' => $isLaptop ? rand(350000, 560000) : rand(210000, 3500000),
                'rental_monthly_rate' => $isLaptop ? rand(150000, 2400000) : rand(900000, 1500000),
                'warranty_period' => $i < 8 ? '24 months' : '12 months',
                'minimum_stock' => rand(3, 10),
                'is_active' => true,
                'is_borrowable' => true,
                'image_path' => $isLaptop ? 'images/laptop.svg' : 'images/desktop.svg',
            ]);

            $this->createUnits($product, $isLaptop ? 'LPT' : 'DSK');
        }
    }

    private function seedAccessories(Category $category): void
    {
        $accessories = [
            ['name' => 'SSD NVMe 512GB', 'component' => 'SSD', 'capacity' => '512 GB', 'conn' => 'NVMe PCIe 4.0', 'compat' => 'M.2 2280', 'purchase' => 550000, 'sell' => 850000, 'rent' => 25000],
            ['name' => 'SSD NVMe 1TB', 'component' => 'SSD', 'capacity' => '1 TB', 'conn' => 'NVMe PCIe 4.0', 'compat' => 'M.2 2280', 'purchase' => 950000, 'sell' => 1450000, 'rent' => 35000],
            ['name' => 'SSD SATA 480GB', 'component' => 'SSD', 'capacity' => '480 GB', 'conn' => 'SATA III', 'compat' => '2.5 inch', 'purchase' => 480000, 'sell' => 700000, 'rent' => 20000],
            ['name' => 'RAM DDR4 8GB', 'component' => 'RAM', 'capacity' => '8 GB', 'conn' => 'DDR4-3200', 'compat' => 'SO-DIMM / U-DIMM', 'purchase' => 320000, 'sell' => 520000, 'rent' => 15000],
            ['name' => 'RAM DDR4 16GB', 'component' => 'RAM', 'capacity' => '16 GB', 'conn' => 'DDR4-3200', 'compat' => 'SO-DIMM / U-DIMM', 'purchase' => 580000, 'sell' => 900000, 'rent' => 25000],
            ['name' => 'RAM DDR5 16GB', 'component' => 'RAM', 'capacity' => '16 GB', 'conn' => 'DDR5-5600', 'compat' => 'SO-DIMM / U-DIMM', 'purchase' => 850000, 'sell' => 1250000, 'rent' => 30000],
            ['name' => 'Mouse Wireless Logitech M280', 'component' => 'Mouse', 'capacity' => null, 'conn' => 'USB-A Wireless 2.4GHz', 'compat' => 'Windows / macOS / Linux', 'purchase' => 120000, 'sell' => 195000, 'rent' => 8000],
            ['name' => 'Mouse Gaming RGB', 'component' => 'Mouse', 'capacity' => null, 'conn' => 'USB-A Wired', 'compat' => 'Windows / macOS', 'purchase' => 180000, 'sell' => 275000, 'rent' => 10000],
            ['name' => 'Keyboard Mechanical K3', 'component' => 'Keyboard', 'capacity' => null, 'conn' => 'USB-C / Bluetooth', 'compat' => 'Windows / macOS', 'purchase' => 650000, 'sell' => 950000, 'rent' => 25000],
            ['name' => 'Keyboard Wireless Logitech K380', 'component' => 'Keyboard', 'capacity' => null, 'conn' => 'Bluetooth', 'compat' => 'Windows / macOS / Android / iOS', 'purchase' => 350000, 'sell' => 520000, 'rent' => 18000],
            ['name' => 'Monitor LED 24" Full HD', 'component' => 'Monitor', 'capacity' => null, 'conn' => 'HDMI / VGA', 'compat' => 'Universal', 'purchase' => 1300000, 'sell' => 1850000, 'rent' => 45000],
            ['name' => 'Headset Gaming', 'component' => 'Headset', 'capacity' => null, 'conn' => 'USB / 3.5mm Jack', 'compat' => 'Windows / PS / Console', 'purchase' => 250000, 'sell' => 400000, 'rent' => 12000],
            ['name' => 'Webcam Full HD 1080p', 'component' => 'Webcam', 'capacity' => null, 'conn' => 'USB-A', 'compat' => 'Windows / macOS / Linux', 'purchase' => 320000, 'sell' => 495000, 'rent' => 15000],
            ['name' => 'Kabel HDMI 2.0 2m', 'component' => 'Kabel', 'capacity' => null, 'conn' => 'HDMI Male-Male', 'compat' => 'Universal', 'purchase' => 45000, 'sell' => 85000, 'rent' => 5000],
            ['name' => 'Adapter USB-C to HDMI', 'component' => 'Adapter', 'capacity' => null, 'conn' => 'USB-C', 'compat' => 'Windows / macOS', 'purchase' => 120000, 'sell' => 210000, 'rent' => 8000],
        ];

        foreach ($accessories as $item) {
            $brand = Brand::inRandomOrder()->first();

            $product = Product::create([
                'category_id' => $category->id,
                'brand_id' => $brand?->id,
                'name' => $item['name'],
                'model' => $item['component'],
                'condition' => 'new',
                'component_type' => $item['component'],
                'capacity_gb' => $item['capacity'],
                'connectivity' => $item['conn'],
                'compatibility' => $item['compat'],
                'description' => $item['component'].' berkualitas untuk upgrade / kebutuhan komputer Anda.',
                'purchase_price' => $item['purchase'],
                'selling_price' => $item['sell'],
                'rental_daily_rate' => $item['rent'],
                'rental_weekly_rate' => $item['rent'] * 5,
                'rental_monthly_rate' => $item['rent'] * 18,
                'warranty_period' => '12 months',
                'minimum_stock' => rand(3, 8),
                'is_active' => true,
                'is_borrowable' => true,
                'image_path' => 'images/accessory.svg',
            ]);

            $this->createUnits($product, 'ACC');
        }
    }

    private function createUnits(Product $product, string $prefix): void
    {
        for ($j = 0; $j < rand(3, 6); $j++) {
            $status = collect(['available', 'available', 'available', 'rented', 'service', 'damaged'])->random();

            $rooms = ['Gudang Utama', 'Ruang Display', 'Gudang Belakang'];
            $racks = ['Rak A', 'Rak B', 'Rak C', 'Rak D'];
            $shelves = ['Tingkat 1', 'Tingkat 2', 'Tingkat 3'];

            ProductUnit::create([
                'product_id' => $product->id,
                'serial_number' => $prefix.str_pad($product->id.'-'.$j, 8, '0', STR_PAD_LEFT),
                'room' => $rooms[array_rand($rooms)],
                'rack' => $racks[array_rand($racks)],
                'shelf' => $shelves[array_rand($shelves)],
                'bin' => 'Box '.str_pad((string) rand(1, 20), 2, '0', STR_PAD_LEFT),
                'physical_condition' => in_array($status, ['available', 'rented']) ? 'baik' : $status,
                'status' => $status,
            ]);
        }
    }
}
