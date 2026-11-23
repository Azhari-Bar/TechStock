<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductUnitController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\RentalAdminController;
use App\Http\Controllers\Admin\StockMovementController;
use App\Http\Controllers\Admin\SupplierController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->group(function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('brands', BrandController::class);
        Route::resource('products', ProductController::class);
        Route::resource('product-units', ProductUnitController::class);
        Route::resource('stock-movements', StockMovementController::class)->only(['index', 'create', 'store', 'show']);
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            // Filter periode sama untuk keempat laporan: /{period?}/{date?} -> year|month|week
            Route::get('/export-rentals/{period?}/{date?}', [ReportController::class, 'exportRentals'])->name('export.rentals');
            Route::get('/export-products/{period?}/{date?}', [ReportController::class, 'exportProducts'])->name('export.products');
            Route::get('/export-units/{period?}/{date?}', [ReportController::class, 'exportUnits'])->name('export.units');
            Route::get('/export-stock-movements/{period?}/{date?}', [ReportController::class, 'exportStockMovements'])->name('export.stock-movements');
        });

        // Rentals
        Route::resource('rentals', RentalAdminController::class)->only(['index', 'show']);
        Route::post('rentals/{rental}/approve', [RentalAdminController::class, 'approve'])->name('rentals.approve');
        Route::post('rentals/{rental}/handover', [RentalAdminController::class, 'handover'])->name('rentals.handover');
        Route::post('rentals/{rental}/return', [RentalAdminController::class, 'returnUnit'])->name('rentals.return');
        Route::post('rentals/{rental}/cancel', [RentalAdminController::class, 'cancel'])->name('rentals.cancel');
        Route::post('rentals/{rental}/payment/verify', [RentalAdminController::class, 'verifyPayment'])->name('rentals.payment.verify');
        Route::get('rentals/{rental}/identity', [RentalAdminController::class, 'identityDocument'])->name('rentals.identity');

        Route::resource('suppliers', SupplierController::class);
    });
});
