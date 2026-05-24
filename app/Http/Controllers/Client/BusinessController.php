<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Business;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::where('status', 'active')->with('products')->paginate(12);
        return view('store.businesses', compact('businesses'));
    }

    public function show(Business $business)
    {
        $business->load(['products' => fn($q) => $q->where('status', 'available')]);
        $business->load(['reviews.user']);
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('products.id')->toArray() : [];
        $hasReviewed = auth()->check() ? $business->reviews()->where('user_id', auth()->id())->exists() : false;
        return view('store.business', compact('business', 'favorites', 'hasReviewed'));
    }
}
