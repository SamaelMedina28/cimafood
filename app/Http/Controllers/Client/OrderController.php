<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'business_id' => 'required|exists:businesses,id',
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $businessId = $request->input('business_id');
        $items = $request->input('items');

        // Fetch products to get accurate prices directly from DB
        $productIds = collect($items)->pluck('id');
        $products = Product::whereIn('id', $productIds)->where('business_id', $businessId)->get()->keyBy('id');

        if ($products->count() !== count($items)) {
            return response()->json(['error' => 'Algunos productos son inválidos o no pertenecen a este negocio.'], 400);
        }

        $totalPrice = 0;
        $orderProducts = [];

        foreach ($items as $item) {
            $product = $products[$item['id']];
            $quantity = $item['quantity'];
            $subtotal = $product->price * $quantity;
            $totalPrice += $subtotal;

            $orderProducts[$product->id] = [
                'quantity' => $quantity,
                'price_unit' => $product->price,
                'subtotal' => $subtotal,
            ];
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'business_id' => $businessId,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        $order->products()->attach($orderProducts);

        return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 201);
    }
}
