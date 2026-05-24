<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client\BusinessController as ClientBusinessController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\FavoriteController as ClientFavoriteController;
use App\Http\Controllers\Client\ReviewController as ClientReviewController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  //Si no es vendor redirigir a tienda
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  // Admin - Solo vendors pueden acceder
  Route::middleware('is_vendor')->group(function () {
    Route::resource('/business', BusinessController::class);
    Route::resource('/product', ProductController::class);
    Route::resource('/order', OrderController::class);
  });

  // Cliente
  Route::prefix('store')->group(function () {
    Route::get('/', function () {
      return view('store.dashboard');
    })->name('store');
    //Negocios
    Route::get('/businesses', [ClientBusinessController::class, 'index'])->name('store.businesses');
    Route::get('/business/{business}', [ClientBusinessController::class, 'show'])->name('store.business');
    //Productos
    Route::get('/products', [ClientProductController::class, 'index'])->name('store.products');

    //Favoritos
    Route::get('/favorites', [ClientFavoriteController::class, 'index'])->name('store.favorites');
    Route::post('/favorites/toggle/{product}', [ClientFavoriteController::class, 'toggle'])->name('store.favorites.toggle');
    //Reseñas
    Route::post('/reviews', [ClientReviewController::class, 'store'])->name('store.reviews.store');
    //Pedidos
    Route::post('/checkout', [ClientOrderController::class, 'store'])->name('store.checkout');
    Route::get('/orders', [ClientOrderController::class, 'index'])->name('store.orders');
  });
});
