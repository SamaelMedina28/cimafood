<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Business;

class BusinessController extends Controller
{
    public function index()
    {
        $businesses = Business::where('status', 'active')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->paginate(12);

        return view('store.businesses', compact('businesses'));
    }

    public function show(Business $business)
    {
        // Un negocio oculto no se puede ver desde la tienda
        abort_if($business->status !== 'active', 404);

        $business->load([
            'products' => fn($q) => $q->where('status', 'available'),
            'reviews.user',
        ]);

        $user = auth()->user();
        $favorites = $user->favorites()->pluck('products.id')->toArray();
        $hasReviewed = $business->reviews->contains('user_id', $user->id);
        $canReview = $business->canBeReviewedBy($user);

        return view('store.business', compact('business', 'favorites', 'hasReviewed', 'canReview'));
    }
}
