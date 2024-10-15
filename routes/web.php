<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\PaymentController;


// Language switching route
Route::get('language/{lang}', [LanguageController::class, 'switch'])->name('language.switch');

// Home route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authentication routes
Route::middleware('auth')->group(function () {
    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Client management routes
    Route::resource('clients', ClientController::class);

    // Product management routes
    Route::resource('products', ProductController::class);

    // Service management routes
    Route::resource('services', ServiceController::class);

    // Invoice management routes
    Route::resource('invoices', InvoiceController::class);

    // Nested Payment routes under Invoices
    Route::get('invoices/{invoice}/payments/create', [PaymentController::class, 'create'])->name('invoices.payments.create');
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('invoices.payments.store');
    Route::get('invoices/{invoice}/payments', [PaymentController::class, 'index'])->name('invoices.payments.index');

    // Individual payment routes
    Route::resource('/payments', PaymentController::class);
    Route::post('/invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('payments.store');
    // Payment creation for a specific invoice
    Route::post('invoices/{invoice}/payments', [PaymentController::class, 'store'])->name('invoices.payments.store');

    Route::get('/payments', [PaymentController::class, 'allPayments'])->name('payments.index');


    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');

    // Add the existing invoice item routes
    Route::get('invoices/{invoice}/add-items', [InvoiceController::class, 'addItems'])
        ->name('invoices.add-items');
    Route::post('invoices/{invoice}/store-items', [InvoiceController::class, 'storeItems'])
        ->name('invoices.store-items');




});

require __DIR__ . '/auth.php';
