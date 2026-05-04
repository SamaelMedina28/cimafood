<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Client\BusinessController as ClientBusinessController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\FavoriteController as ClientFavoriteController;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  //Si no es vendor redirigir a tienda
  Route::get('/dashboard', function () {
    if (Auth::user()->is_vendor) {
      return view('dashboard');
    } else {
      return redirect()->route('store');
    }
  })->name('dashboard');

  // Vistas de admin
  Route::resource('/business', BusinessController::class);
  Route::resource('/product', ProductController::class);
  Route::resource('/order', OrderController::class);


  // Vistas de cliente
  // Conjunto de rutas con prefijo store
  Route::prefix('store')->group(function () {
    Route::get('/', function () {
      return view('client.dashboard');
    })->name('store');
    Route::get('/business/{business}', [ClientBusinessController::class, 'show'])->name('store.business');
    Route::post('/checkout', [ClientOrderController::class, 'store'])->name('store.checkout');
    Route::get('/favorites', [ClientFavoriteController::class, 'index'])->name('store.favorites');
    Route::post('/favorites/toggle/{product}', [ClientFavoriteController::class, 'toggle'])->name('store.favorites.toggle');
  });
});
