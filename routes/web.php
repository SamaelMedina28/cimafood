<?php

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

    Route::get('/business/create', function () {
        return "<h1 class='text-3xl font-bold underline'>hola</h1>";
    })->name('business.create');

    Route::get('/business', function () {
        dd(auth()->user());
        return "<h1 class='text-3xl font-bold underline'>hola</h1>";
    })->name('business.index');
});
