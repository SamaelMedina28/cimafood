<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'available')
            ->with('business')
            ->withCount(['orders as total_sold' => function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw('SUM(order_product.quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->paginate(20);
        return view('store.products', compact('products'));
    }
}
