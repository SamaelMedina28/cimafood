<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('q'));

        $products = Product::where('status', 'available')
            ->when($search, fn($q) => $q->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            }))
            ->whereHas('business', fn($q) => $q->where('status', 'active'))
            ->with('business')
            ->withSum(['orders as total_sold' => fn($q) => $q->where('status', '!=', 'cancelled')], 'order_product.quantity')
            ->orderByDesc('total_sold')
            ->paginate(20);
        return view('store.products', compact('products', 'search'));
    }

    public function show(Product $product)
    {
        $product->load('business');

        abort_if($product->status !== 'available' || $product->business->status !== 'active', 404);

        $isFavorite = auth()->user()->favorites()->where('product_id', $product->id)->exists();
        $related = $product->business->products()
            ->where('status', 'available')
            ->whereKeyNot($product->id)
            ->limit(4)
            ->get();

        return view('store.product', compact('product', 'isFavorite', 'related'));
    }
}
