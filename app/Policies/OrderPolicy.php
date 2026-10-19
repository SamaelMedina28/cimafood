<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    // El vendedor dueño del negocio puede ver y cambiar el estado del pedido
    public function manage(User $user, Order $order): bool
    {
        return $user->is_vendor && $order->business?->user_id === $user->id;
    }

    // El cliente solo puede cancelar sus propios pedidos mientras sigan pendientes
    public function cancel(User $user, Order $order): bool
    {
        return $order->user_id === $user->id && $order->status === 'pending';
    }
}
