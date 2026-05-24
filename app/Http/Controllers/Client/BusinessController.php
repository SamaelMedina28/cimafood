<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Business;

class BusinessController extends Controller
{
    public function show(Business $business)
    {
        $business->load(['products' => fn($q) => $q->where('status', 'available')]);
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('products.id')->toArray() : [];
        return view('store.business', compact('business', 'favorites'));
    }
}
