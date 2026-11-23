<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->string('room', 100)->nullable()->after('serial_number');
            $table->string('rack', 100)->nullable()->after('room');
            $table->string('shelf', 100)->nullable()->after('rack');
            $table->string('bin', 100)->nullable()->after('shelf');
        });
    }

    public function down(): void
    {
        Schema::table('product_units', function (Blueprint $table) {
            $table->dropColumn(['room', 'rack', 'shelf', 'bin']);
        });
    }
};
