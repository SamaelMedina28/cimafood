<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use App\Models\Business;
use App\Models\Product;
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
  Route::get('/store', function () {
    // TODO:ordenar negocios por calificacion
    $businesses = Business::limit(10)->get();
    $products = Product::all();
    return view('client.dashboard', compact('businesses', 'products'));
  })->name('store');
});
