<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_clients_with_a_completed_order_can_review_once(): void
    {
        $client = User::factory()->create();
        $product = Product::factory()->create();
        $business = $product->business;
        $payload = ['business_id' => $business->id, 'rating' => 4, 'comment' => 'Muy bueno'];

        // Sin pedidos no puede reseñar
        $this->actingAs($client)->postJson(route('store.reviews.store'), $payload)
            ->assertUnprocessable();

        // Con un pedido entregado sí, y se actualiza la calificación del negocio
        $order = app(OrderService::class)->place($client, [['id' => $product->id, 'quantity' => 1]])->first();
        app(OrderService::class)->changeStatus($order, 'completed');

        $this->actingAs($client)->postJson(route('store.reviews.store'), $payload)->assertOk();
        $this->assertEquals(4, $business->fresh()->rating);

        // Una segunda reseña se rechaza
        $this->actingAs($client)->postJson(route('store.reviews.store'), $payload)
            ->assertUnprocessable();
        $this->assertSame(1, Review::count());
    }

    public function test_business_id_is_validated(): void
    {
        $client = User::factory()->create();

        $this->actingAs($client)
            ->postJson(route('store.reviews.store'), ['business_id' => 999, 'rating' => 5])
            ->assertJsonValidationErrors('business_id');
    }
}
