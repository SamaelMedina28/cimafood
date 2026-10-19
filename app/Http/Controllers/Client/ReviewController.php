<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_id' => 'required|integer|exists:businesses,id',
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string|max:1000',
        ]);

        $business = Business::findOrFail($validated['business_id']);
        $user = Auth::user();

        if (!$business->canBeReviewedBy($user)) {
            throw ValidationException::withMessages([
                'business_id' => $business->reviews()->where('user_id', $user->id)->exists()
                    ? 'Ya dejaste una reseña para este negocio.'
                    : 'Solo puedes reseñar negocios donde ya recibiste un pedido.',
            ]);
        }

        $review = Review::create([
            'user_id'     => $user->id,
            'business_id' => $business->id,
            'rating'      => $validated['rating'],
            'comment'     => $validated['comment'] ?? null,
        ]);

        $business->refreshRating();

        return response()->json([
            'success' => true,
            'review'  => $review,
        ]);
    }
}
