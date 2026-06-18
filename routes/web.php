<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PlanController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\SubscriptionController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Plans - Public
Route::get('/plans', [PlanController::class, 'index'])->name('plans.index');
Route::get('/plans/{plan}', [PlanController::class, 'show'])->name('plans.show');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Protected Checkout & Subscription routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/checkout/snap-token', [CheckoutController::class, 'getSnapToken'])->name('checkout.snap-token');
    Route::get('/checkout/callback', [CheckoutController::class, 'callback'])->name('checkout.callback');
    Route::get('/checkout/{plan}', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'processPayment'])->name('process-payment');
    
    // User subscription dashboard
    Route::get('/dashboard/subscription', [SubscriptionController::class, 'index'])->name('subscription.index');
    Route::post('/subscription/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscription.cancel');
});

// Admin routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Plans management
    Route::resource('plans', \App\Http\Controllers\Admin\PlanAdminController::class);
    
    // Subscriptions management
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionAdminController::class, ['only' => ['index', 'show']]);
    Route::post('/subscriptions/{subscription}/upgrade', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'upgrade'])
        ->name('subscriptions.upgrade');
    Route::post('/subscriptions/{subscription}/downgrade', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'downgrade'])
        ->name('subscriptions.downgrade');
    Route::post('/subscriptions/{subscription}/cancel', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'cancel'])
        ->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/renew', [\App\Http\Controllers\Admin\SubscriptionAdminController::class, 'renew'])
        ->name('subscriptions.renew');

    // Invoices management
    Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceAdminController::class, ['only' => ['index', 'show']]);
    Route::post('/invoices/{invoice}/send', [\App\Http\Controllers\Admin\InvoiceAdminController::class, 'send'])
        ->name('invoices.send');

    // Analytics
    Route::get('/analytics', \App\Http\Controllers\Admin\AnalyticsController::class)->name('analytics');
});

require __DIR__.'/auth.php';
