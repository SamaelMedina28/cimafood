<?php

namespace Tests\Feature;

use App\Livewire\Business\AllOrders;
use App\Livewire\Business\Orders;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    private function placeOrder(Product $product, int $quantity = 2, ?User $client = null): Order
    {
        $client ??= User::factory()->create();

        return app(OrderService::class)
            ->place($client, [['id' => $product->id, 'quantity' => $quantity]])
            ->first();
    }

    public function test_cancelling_restores_stock_only_once(): void
    {
        $product = Product::factory()->create(['quantity' => 10]);
        $order = $this->placeOrder($product, 4);
        $vendor = $product->business->user;

        $component = Livewire::actingAs($vendor)->test(AllOrders::class);
        $component->call('updateStatus', $order->id, 'cancelled');
        $component->call('updateStatus', $order->id, 'cancelled');
        $component->call('updateStatus', $order->id, 'pending');

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(10, $product->fresh()->quantity);
    }

    public function test_vendor_cannot_change_orders_of_another_vendor(): void
    {
        $product = Product::factory()->create();
        $order = $this->placeOrder($product);
        $otherVendor = User::factory()->vendor()->create();

        // Los pedidos se buscan solo entre los negocios del vendedor, así que el ajeno "no existe"
        $this->assertThrows(fn() => Livewire::actingAs($otherVendor)->test(AllOrders::class)
            ->call('updateStatus', $order->id, 'completed'), ModelNotFoundException::class);

        $this->assertThrows(fn() => Livewire::actingAs($otherVendor)->test(AllOrders::class)
            ->call('viewOrder', $order->id), ModelNotFoundException::class);

        $this->assertSame('pending', $order->fresh()->status);
    }

    public function test_vendor_cannot_open_orders_component_of_another_business(): void
    {
        $product = Product::factory()->create();
        $otherVendor = User::factory()->vendor()->create();

        Livewire::actingAs($otherVendor)->test(Orders::class, ['business' => $product->business])
            ->assertForbidden();
    }

    public function test_client_can_cancel_own_pending_order(): void
    {
        $client = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 5]);
        $order = $this->placeOrder($product, 2, $client);

        $this->actingAs($client)
            ->post(route('store.orders.cancel', $order))
            ->assertRedirect(route('store.orders'));

        $this->assertSame('cancelled', $order->fresh()->status);
        $this->assertSame(5, $product->fresh()->quantity);
    }

    public function test_client_cannot_cancel_others_or_completed_orders(): void
    {
        $product = Product::factory()->create();
        $order = $this->placeOrder($product);
        $stranger = User::factory()->create();

        $this->actingAs($stranger)->post(route('store.orders.cancel', $order))->assertForbidden();

        app(OrderService::class)->changeStatus($order, 'completed');
        $this->actingAs($order->user)->post(route('store.orders.cancel', $order))->assertForbidden();

        $this->assertSame('completed', $order->fresh()->status);
    }
}
