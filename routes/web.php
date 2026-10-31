<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Shop\AccountOrderController;
use App\Http\Controllers\Shop\CartController;
use App\Http\Controllers\Shop\CheckoutController;
use App\Http\Controllers\Shop\HomeController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Negozio (pubblico)
|--------------------------------------------------------------------------
*/
Route::get('/', HomeController::class)->name('home');

Route::get('/prodotti', [ProductController::class, 'index'])->name('products.index');
Route::get('/prodotti/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categoria/{category}', [ProductController::class, 'index'])->name('categories.show');

Route::prefix('carrello')->name('cart.')->controller(CartController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/coupon', 'applyCoupon')->name('coupon.apply');
    Route::delete('/coupon', 'removeCoupon')->name('coupon.remove');
    Route::post('/{product}', 'store')->name('store');
    Route::patch('/{product}', 'update')->name('update');
    Route::delete('/{product}', 'destroy')->name('destroy');
});

Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
    Route::get('/', 'create')->name('create');
    Route::post('/', 'store')->middleware('throttle:10,1')->name('store');
    Route::get('/completato/{order}', 'success')->name('success');
    Route::get('/annullato/{order}', 'cancel')->name('cancel');
});

Route::post('/webhooks/stripe', StripeWebhookController::class)->name('webhooks.stripe');

/*
|--------------------------------------------------------------------------
| Autenticazione
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
    Route::get('/registrati', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/registrati', [RegisteredUserController::class, 'store']);
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Area cliente
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/ordini', [AccountOrderController::class, 'index'])->name('orders.index');
    Route::get('/ordini/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
});

/*
|--------------------------------------------------------------------------
| Amministrazione
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Admin\DashboardController::class)->name('dashboard');
    Route::resource('products', Admin\ProductController::class)->except('show');
    Route::resource('categories', Admin\CategoryController::class)->except('show');
    Route::resource('orders', Admin\OrderController::class)->only(['index', 'show', 'update']);
});
