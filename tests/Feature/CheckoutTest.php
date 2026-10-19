<?php

namespace Tests\Feature;

use App\Models\Business;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private function client(): User
    {
        return User::factory()->create();
    }

    public function test_creates_one_order_per_business_and_decrements_stock(): void
    {
        $tacos = Product::factory()->create(['price' => 20, 'quantity' => 10]);
        $sushi = Product::factory()->create(['price' => 100, 'quantity' => 5]);

        $this->actingAs($this->client())
            ->postJson(route('store.checkout'), ['items' => [
                ['id' => $tacos->id, 'quantity' => 3],
                ['id' => $sushi->id, 'quantity' => 1],
            ]])
            ->assertCreated();

        $this->assertSame(2, Order::count());
        $this->assertEquals(60, Order::where('business_id', $tacos->business_id)->value('total_price'));
        $this->assertSame(7, $tacos->fresh()->quantity);
        $this->assertSame(4, $sushi->fresh()->quantity);
    }

    public function test_no_order_is_created_when_one_item_lacks_stock(): void
    {
        $available = Product::factory()->create(['quantity' => 10]);
        $scarce = Product::factory()->create(['quantity' => 1]);

        $this->actingAs($this->client())
            ->postJson(route('store.checkout'), ['items' => [
                ['id' => $available->id, 'quantity' => 2],
                ['id' => $scarce->id, 'quantity' => 2],
            ]])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('items');

        // Todo o nada: ni el pedido válido se crea ni se toca el stock
        $this->assertSame(0, Order::count());
        $this->assertSame(10, $available->fresh()->quantity);
        $this->assertSame(1, $scarce->fresh()->quantity);
    }

    public function test_rejects_unavailable_products_and_inactive_or_closed_businesses(): void
    {
        $unavailable = Product::factory()->create(['status' => 'unavailable']);
        $hidden = Product::factory()->for(Business::factory()->state(['status' => 'inactive']))->create();
        $closedAt = now()->addHours(2)->format('H:i');
        $closed = Product::factory()->for(Business::factory()->state([
            'open_time' => $closedAt,
            'close_time' => now()->addHours(3)->format('H:i'),
        ]))->create();

        foreach ([$unavailable, $hidden, $closed] as $product) {
            $this->actingAs($this->client())
                ->postJson(route('store.checkout'), ['items' => [['id' => $product->id, 'quantity' => 1]]])
                ->assertUnprocessable();
        }

        $this->assertSame(0, Order::count());
    }

    public function test_repeated_items_are_merged_before_checking_stock(): void
    {
        $product = Product::factory()->create(['quantity' => 3]);

        $this->actingAs($this->client())
            ->postJson(route('store.checkout'), ['items' => [
                ['id' => $product->id, 'quantity' => 2],
                ['id' => $product->id, 'quantity' => 2],
            ]])
            ->assertUnprocessable();

        $this->assertSame(3, $product->fresh()->quantity);
    }
}
