<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Pedidos de ejemplo de la clienta Ana en varios estados.
     * Se crean directamente (sin OrderService) para no depender del horario del negocio.
     */
    public function run(): void
    {
        $client = User::where('email', 'ana@cliente.com')->first();

        $orders = [
            // [negocio, [producto => cantidad], estado, hace cuántos días]
            ['Burritos El Mexicano', ['Burrito de Carne Asada' => 2, 'Burrito de Pollo' => 1], 'completed', 6],
            ['Hamburguesas La Gorda', ['Hamburguesa Clásica' => 1, 'Hamburguesa Doble' => 1], 'completed', 4],
            ['Sushi del Pacífico', ['California Roll' => 2], 'completed', 3],
            ['Tacos y Burritos Don Carlos', ['Tacos de Pastor' => 3], 'cancelled', 2],
            ['Tacos y Burritos Don Carlos', ['Quesadilla Grande' => 1, 'Tacos de Carnitas' => 1], 'pending', 0],
        ];

        foreach ($orders as [$businessName, $items, $status, $daysAgo]) {
            $business = Business::where('name', $businessName)->first();
            $lines = [];
            $total = 0;

            foreach ($items as $productName => $quantity) {
                $product = $business->products()->where('name', $productName)->first();
                $subtotal = $product->price * $quantity;
                $total += $subtotal;
                $lines[$product->id] = ['quantity' => $quantity, 'price_unit' => $product->price, 'subtotal' => $subtotal];

                // Los pedidos cancelados ya devolvieron su stock
                if ($status !== 'cancelled') {
                    $product->decrement('quantity', $quantity);
                }
            }

            $order = Order::create([
                'user_id' => $client->id,
                'business_id' => $business->id,
                'total_price' => $total,
                'status' => $status,
            ]);
            $order->products()->attach($lines);

            $order->created_at = now()->subDays($daysAgo)->setTime(13, 30);
            $order->save();
        }
    }
}
