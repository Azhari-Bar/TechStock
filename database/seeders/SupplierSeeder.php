<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        if (Supplier::exists()) {
            return;
        }

        $suppliers = [
            ['name' => 'PT Sumber Komputer Jaya', 'contact_person' => 'Budi Santoso', 'phone' => '021-5551234', 'email' => 'sales@sumberkomputer.co.id', 'address' => 'Jl. Mangga Dua Raya No. 12, Jakarta Utara'],
            ['name' => 'CV Teknologi Mandiri', 'contact_person' => 'Siti Rahayu', 'phone' => '021-5559876', 'email' => 'order@teknologimandiri.co.id', 'address' => 'Jl. Harco Glodok Lt. 3, Jakarta Barat'],
            ['name' => 'PT Global Elektronik', 'contact_person' => 'Andi Wijaya', 'phone' => '031-8887766', 'email' => 'info@globalelektronik.co.id', 'address' => 'Jl. Rungkut Industri No. 45, Surabaya'],
            ['name' => 'Toko Aksesoris Komputer Sejahtera', 'contact_person' => 'Rina Kusuma', 'phone' => '0812-3456-7890', 'email' => 'rinakusuma@gmail.com', 'address' => 'Jl. Bhinneka Jaya No. 88, Bandung'],
            ['name' => 'PT Distribusi Perangkat Nusantara', 'contact_person' => 'Hendra Gunawan', 'phone' => '024-7778899', 'email' => 'purchasing@dpn.co.id', 'address' => 'Jl. Majapahit No. 200, Semarang'],
        ];

        foreach ($suppliers as $data) {
            Supplier::create([
                'name' => $data['name'],
                'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
                'contact_person' => $data['contact_person'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'is_active' => true,
            ]);
        }
    }
}
