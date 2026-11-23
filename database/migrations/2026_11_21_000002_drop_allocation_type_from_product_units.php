<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Simplify: every physical unit is part of the same warehouse stock.
        // Allocation (sale vs rental) is no longer tracked separately.
        Schema::table('product_units', function (Blueprint $table) {
            if (Schema::hasColumn('product_units', 'allocation_type')) {
                $table->dropColumn('allocation_type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->string('allocation_type', 20)->default('sale')->after('serial_number');
        });
    }
};