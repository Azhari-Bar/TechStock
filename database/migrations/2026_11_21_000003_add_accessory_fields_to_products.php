<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Fields for components/accessories
            $table->string('component_type', 50)->nullable()->after('storage_type');
            // e.g., SSD, RAM, Mouse, Keyboard, Monitor, Cable, Adapter, etc.
            
            $table->string('capacity_gb', 50)->nullable()->after('component_type');
            // For SSD/RAM: 256GB, 512GB, 8GB, 16GB, etc.
            
            $table->string('connectivity', 50)->nullable()->after('capacity_gb');
            // SATA, NVMe, USB-A, USB-C, HDMI, etc.
            
            $table->string('compatibility', 255)->nullable()->after('connectivity');
            // e.g., "DDR4", "Intel LGA1700", "Windows/Linux"
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['component_type', 'capacity_gb', 'connectivity', 'compatibility']);
        });
    }
};
