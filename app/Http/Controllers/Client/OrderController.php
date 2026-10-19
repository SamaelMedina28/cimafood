<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()
            ->with(['business', 'products'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('store.orders', compact('orders'));
    }

    // Recibe el carrito completo (puede traer productos de varios negocios)
    // y crea un pedido por negocio en una sola transacción.
    public function store(Request $request, OrderService $orders)
    {
        $validated = $request->validate([
            'items'            => 'required|array|min:1',
            'items.*.id'       => 'required|integer',
            'items.*.quantity' => 'required|integer|min:1|max:99',
        ]);

        $created = $orders->place(Auth::user(), $validated['items']);

        $count = $created->count();
        session()->flash('flash.banner', $count === 1
            ? 'Pedido enviado. Puedes seguir su estado aquí.'
            : "Se enviaron {$count} pedidos, uno por negocio. Puedes seguir su estado aquí.");
        session()->flash('flash.bannerStyle', 'success');

        return response()->json([
            'message'   => 'Pedido creado exitosamente.',
            'order_ids' => $created->pluck('id'),
        ], 201);
    }

    public function cancel(Order $order, OrderService $orders)
    {
        Gate::authorize('cancel', $order);

        $orders->changeStatus($order, 'cancelled');

        return redirect()->route('store.orders')->with([
            'flash.banner' => "Cancelaste el pedido #{$order->id}.",
            'flash.bannerStyle' => 'success',
        ]);
    }
}
