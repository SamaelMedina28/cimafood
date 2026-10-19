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
            ->with('business')
            ->withCount(['orders as total_sold' => function ($query) {
                $query->select(\Illuminate\Support\Facades\DB::raw('SUM(order_product.quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->paginate(20);
        return view('store.products', compact('products', 'search'));
    }
}
