<?php

use App\Http\Controllers\PublicCatalogController;
use Illuminate\Support\Facades\Route;

// --- Public Catalog (no login) ---
Route::get('/', [PublicCatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{product}', [PublicCatalogController::class, 'show'])->name('catalog.show');

Route::prefix('rentals')->name('rentals.')->group(function () {
    Route::get('/request', [\App\Http\Controllers\RentalController::class, 'create'])->name('create');
    Route::post('/request', [\App\Http\Controllers\RentalController::class, 'store'])->name('store');
    Route::get('/detail/{reference}', [\App\Http\Controllers\RentalController::class, 'show'])->name('show');
    Route::get('/success', [\App\Http\Controllers\RentalController::class, 'success'])->name('success');
    Route::get('/{rental}/pay', [\App\Http\Controllers\RentalController::class, 'proofForm'])->name('payment.form');
    Route::post('/{rental}/pay', [\App\Http\Controllers\RentalController::class, 'submitProof'])->name('payment.submit');
});

// Support page
Route::get('/support', function () {
    return view('support.index');
})->name('support.index');

// Alias /admin/login -> /login so "Masuk Admin" link juga berfungsi
Route::redirect('admin/login', '/login');

require __DIR__.'/auth.php';

// Admin routes
require __DIR__.'/admin.php';