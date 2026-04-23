<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome');
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('/dashboard', function () {
    return view('dashboard');
  })->name('dashboard');

  // Vistas de admin
  Route::resource('/business', BusinessController::class);
  Route::resource('/product', ProductController::class);
  Route::resource('/order', OrderController::class);


  // Vistas de cliente
  Route::get('/store', function () {
    return view('client.prueba');
  })->name('store');
});
