<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        $user = auth()->user();

        if ($user->favorites()->where('product_id', $product->id)->exists()) {
            $user->favorites()->detach($product->id);
            $isFavorite = false;
        } else {
            $user->favorites()->attach($product->id);
            $isFavorite = true;
        }

        return response()->json([
            'isFavorite' => $isFavorite
        ]);
    }
}
