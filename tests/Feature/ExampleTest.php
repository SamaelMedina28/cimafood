<?php

namespace Tests\Feature;

use App\Models\Business;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_home_lists_only_active_businesses(): void
    {
        Business::factory()->create(['name' => 'Tacos Visibles']);
        Business::factory()->create(['name' => 'Negocio Oculto', 'status' => 'inactive']);

        $this->get('/')
            ->assertSee('Tacos Visibles')
            ->assertDontSee('Negocio Oculto');
    }
}
