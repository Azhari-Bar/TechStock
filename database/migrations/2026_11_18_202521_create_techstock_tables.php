<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add role and is_active to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('admin')->after('password');
            $table->boolean('is_active')->default(true)->after('role');
            $table->softDeletes();
        });

        // categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->string('type', 20)->default('laptop'); // laptop | desktop
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // brands
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 150)->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        // products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('brand_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('slug', 300)->unique();
            $table->string('model', 100)->nullable();
            $table->string('condition', 10)->default('new'); // new | used
            $table->string('processor', 150)->nullable();
            $table->decimal('ram_gb', 5, 2)->nullable();
            $table->decimal('storage_capacity', 8, 2)->nullable();
            $table->string('storage_type', 50)->nullable();
            $table->string('gpu', 150)->nullable();
            $table->decimal('screen_size', 5, 2)->nullable();
            $table->string('operating_system', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('image_path', 500)->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('rental_daily_rate', 12, 2)->default(0);
            $table->decimal('rental_weekly_rate', 12, 2)->default(0);
            $table->decimal('rental_monthly_rate', 12, 2)->default(0);
            $table->string('warranty_period', 100)->nullable();
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['category_id', 'brand_id']);
        });

        // product_units
        Schema::create('product_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('serial_number', 100)->unique();
            $table->string('allocation_type', 20)->default('sale'); // sale | rental
            $table->string('physical_condition', 100)->default('baik');
            $table->string('grade', 20)->nullable();
            $table->decimal('acquisition_price', 12, 2)->default(0);
            $table->date('received_at')->nullable();
            $table->date('warranty_until')->nullable();
            $table->string('image_path', 500)->nullable();
            $table->text('notes')->nullable();
            $table->string('status', 30)->default('available');
            $table->timestamps();
            $table->softDeletes();
            $table->index('status');
        });

        // purchases
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

        // purchase_items
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

        // stock_movements
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('movement_type', 30);
            $table->integer('quantity')->default(1);
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('status_before', 30)->nullable();
            $table->string('status_after', 30)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['product_id', 'movement_type']);
            $table->index(['reference_type', 'reference_id']);
        });

        // stock_adjustments
        Schema::create('stock_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique();
            $table->string('reason', 255);
            $table->string('status', 30)->default('pending');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // orders
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

        // order_items
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

        // payments
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->morphs('payable');
            $table->string('payment_type', 50)->default('sales'); // sales | rental | deposit | fine
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('payment_method', 50)->default('transfer');
            $table->string('proof_path', 500)->nullable();
            $table->string('status', 30)->default('pending');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // rentals
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number', 50)->unique();
            $table->string('public_token_hash', 100)->unique();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->nullable()->constrained()->nullOnDelete();
            $table->string('customer_name', 255);
            $table->string('customer_phone', 30);
            $table->string('customer_email', 255)->nullable();
            $table->text('customer_address')->nullable();
            $table->text('purpose')->nullable();
            $table->string('identity_document_path', 500)->nullable();
            $table->date('start_date');
            $table->date('due_date');
            $table->timestamp('returned_at')->nullable();
            $table->string('rental_period_type', 20)->default('daily');
            $table->decimal('rental_rate_snapshot', 12, 2)->default(0);
            $table->decimal('estimated_amount', 15, 2)->default(0);
            $table->decimal('deposit_amount', 12, 2)->default(0);
            $table->string('status', 30)->default('menunggu');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('status');
            $table->index(['start_date', 'due_date']);
        });

        // rental_handover_checklists
        Schema::create('rental_handover_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->text('condition_notes')->nullable();
            $table->json('accessories')->nullable();
            $table->json('photo_paths')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        // rental_return_checklists
        Schema::create('rental_return_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_unit_id')->constrained()->cascadeOnDelete();
            $table->text('condition_notes')->nullable();
            $table->text('damage_notes')->nullable();
            $table->json('photo_paths')->nullable();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('checked_at')->nullable();
            $table->timestamps();
        });

        // rental_extensions
        Schema::create('rental_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->date('old_due_date');
            $table->date('new_due_date');
            $table->decimal('additional_amount', 12, 2)->default(0);
            $table->text('reason')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // deposits
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status', 30)->default('pending');
            $table->decimal('returned_amount', 12, 2)->default(0);
            $table->decimal('deducted_amount', 12, 2)->default(0);
            $table->timestamp('returned_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // fines
        Schema::create('fines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rental_id')->constrained()->cascadeOnDelete();
            $table->string('type', 50)->default('late_return');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->string('status', 30)->default('unpaid');
            $table->text('reason')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // services
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

        // settings
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string');
            $table->timestamps();
        });

        // notifications
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->json('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('services');
        Schema::dropIfExists('fines');
        Schema::dropIfExists('deposits');
        Schema::dropIfExists('rental_extensions');
        Schema::dropIfExists('rental_return_checklists');
        Schema::dropIfExists('rental_handover_checklists');
        Schema::dropIfExists('rentals');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('stock_adjustments');
        Schema::dropIfExists('stock_movements');
        Schema::dropIfExists('purchase_items');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('product_units');
        Schema::dropIfExists('products');
        Schema::dropIfExists('brands');
        Schema::dropIfExists('categories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
