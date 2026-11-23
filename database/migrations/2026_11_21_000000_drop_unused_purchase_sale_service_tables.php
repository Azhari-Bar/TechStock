<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop tables that are not used (purchase/sale/service related)
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('services');
    }

    public function down(): void
    {
        // Re-create if rollback needed (structure from original migration)
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->text('complaint')->nullable();
            $table->text('damage_description')->nullable();
            $table->string('technician', 150)->nullable();
            $table->decimal('service_cost', 12, 2)->default(0);
            $table->date('service_date');
            $table->date('completed_at')->nullable();
            $table->text('repair_action')->nullable();
            $table->string('status', 30)->default('in_progress');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique();
            $table->string('public_token_hash', 100)->unique();
            $table->string('customer_name', 255);
            $table->string('customer_phone', 30);
            $table->string('customer_email', 255)->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('delivery_method', 50)->nullable();
            $table->string('status', 30)->default('menunggu_konfirmasi');
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->decimal('shipping_cost', 12, 2)->default(0);
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->index('status');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('product_name_snapshot', 255);
            $table->string('serial_number_snapshot', 100)->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique();
            $table->string('invoice_number', 100)->nullable();
            $table->date('purchase_date');
            $table->decimal('total_amount', 15, 2)->default(0);
            $table->string('status', 30)->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->json('serial_numbers')->nullable();
            $table->timestamps();
        });
    }
};