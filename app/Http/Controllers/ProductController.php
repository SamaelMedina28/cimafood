<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function show(Product $product)
    {
        Gate::authorize('manage', $product);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        Gate::authorize('manage', $product);

        return view('products.edit', compact('product'));
    }
}
