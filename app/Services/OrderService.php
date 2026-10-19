<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    // Transiciones de estado permitidas. Un pedido cancelado ya no puede cambiar.
    public const TRANSITIONS = [
        'pending' => ['completed', 'cancelled'],
        'completed' => ['pending', 'cancelled'],
        'cancelled' => [],
    ];

    /**
     * Crea un pedido por cada negocio de los productos recibidos.
     * Todo ocurre en una sola transacción: o se crean todos los pedidos o ninguno.
     *
     * @param  array<int, array{id: int, quantity: int}>  $items
     * @return Collection<int, Order>
     */
    public function place(User $user, array $items): Collection
    {
        // Si el mismo producto viene más de una vez se suman las cantidades
        $quantities = collect($items)
            ->groupBy('id')
            ->map(fn($group) => (int) $group->sum('quantity'));

        return DB::transaction(function () use ($user, $quantities) {
            $products = Product::with('business')
                ->whereIn('id', $quantities->keys())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $errors = [];
            foreach ($quantities as $id => $quantity) {
                $product = $products->get($id);

                if (!$product || $product->status !== 'available' || $product->business->status !== 'active') {
                    $errors[] = $product
                        ? "«{$product->name}» ya no está disponible. Quítalo del carrito para continuar."
                        : 'Uno de los productos ya no existe. Quítalo del carrito para continuar.';
                } elseif (!$product->business->isOpen()) {
                    $errors[] = "{$product->business->name} está cerrado ahora. Abre a las " . substr($product->business->open_time, 0, 5) . '.';
                } elseif ($product->quantity < $quantity) {
                    $errors[] = $product->quantity > 0
                        ? "Solo quedan {$product->quantity} de «{$product->name}». Ajusta la cantidad en el carrito."
                        : "«{$product->name}» se agotó. Quítalo del carrito para continuar.";
                }
            }

            if ($errors) {
                throw ValidationException::withMessages(['items' => array_values(array_unique($errors))]);
            }

            return $quantities
                ->groupBy(fn($quantity, $id) => $products[$id]->business_id, preserveKeys: true)
                ->map(function ($businessItems, $businessId) use ($user, $products) {
                    $lines = [];
                    $total = 0;

                    foreach ($businessItems as $id => $quantity) {
                        $product = $products[$id];
                        $subtotal = $product->price * $quantity;
                        $total += $subtotal;
                        $lines[$id] = [
                            'quantity' => $quantity,
                            'price_unit' => $product->price,
                            'subtotal' => $subtotal,
                        ];

                        $product->decrement('quantity', $quantity);
                    }

                    $order = Order::create([
                        'user_id' => $user->id,
                        'business_id' => $businessId,
                        'total_price' => $total,
                        'status' => 'pending',
                    ]);
                    $order->products()->attach($lines);

                    return $order;
                })
                ->values();
        });
    }

    /**
     * Cambia el estado de un pedido. Al cancelarlo el stock regresa al negocio,
     * y como un pedido cancelado no puede volver a cambiar, el stock solo se devuelve una vez.
     */
    public function changeStatus(Order $order, string $status): Order
    {
        return DB::transaction(function () use ($order, $status) {
            // Releer con bloqueo para evitar dos cambios simultáneos sobre el mismo pedido
            $order = Order::with('products')->lockForUpdate()->findOrFail($order->id);

            if ($order->status === $status) {
                return $order;
            }

            if (!in_array($status, self::TRANSITIONS[$order->status] ?? [], true)) {
                throw ValidationException::withMessages([
                    'status' => 'Un pedido cancelado ya no puede cambiar de estado.',
                ]);
            }

            if ($status === 'cancelled') {
                foreach ($order->products as $product) {
                    Product::whereKey($product->id)->increment('quantity', $product->pivot->quantity);
                }
            }

            $order->update(['status' => $status]);

            return $order;
        });
    }
}
