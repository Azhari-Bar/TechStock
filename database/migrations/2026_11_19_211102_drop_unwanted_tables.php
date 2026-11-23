<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop audit_logs table
        Schema::dropIfExists('audit_logs');
        
        // Drop suppliers table
        Schema::dropIfExists('suppliers');
        
        // Remove supplier_id foreign key from purchases table if exists
        if (Schema::hasTable('purchases') && Schema::hasColumn('purchases', 'supplier_id')) {
            Schema::table('purchases', function (Blueprint $table) {
                $table->dropForeign(['supplier_id']);
                $table->dropColumn('supplier_id');
            });
        }
    }

    public function down(): void
    {
        // Not needed since this is destructive operation
    }
};