<?php

namespace Tests\Feature;

use App\Livewire\Business\FormEdit;
use App\Livewire\Business\Index as BusinessIndex;
use App\Livewire\Product\Create as ProductCreate;
use App\Livewire\Product\Index as ProductIndex;
use App\Models\Business;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class VendorOwnershipTest extends TestCase
{
    use RefreshDatabase;

    private User $intruder;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product = Product::factory()->create();
        $this->intruder = User::factory()->vendor()->create();
    }

    public function test_vendor_cannot_open_pages_of_another_vendor(): void
    {
        $business = $this->product->business;

        $this->actingAs($this->intruder)->get(route('business.show', $business))->assertForbidden();
        $this->actingAs($this->intruder)->get(route('business.edit', $business))->assertForbidden();
        $this->actingAs($this->intruder)->get(route('product.show', $this->product))->assertForbidden();
        $this->actingAs($this->intruder)->get(route('product.edit', $this->product))->assertForbidden();
    }

    public function test_owner_can_open_own_pages(): void
    {
        $owner = $this->product->business->user;

        $this->actingAs($owner)->get(route('business.show', $this->product->business))->assertOk();
        $this->actingAs($owner)->get(route('product.edit', $this->product))->assertOk();
    }

    public function test_vendor_cannot_delete_business_or_product_of_another_vendor(): void
    {
        $this->assertThrows(fn() => Livewire::actingAs($this->intruder)->test(BusinessIndex::class)
            ->set('businessIdToDelete', $this->product->business_id)
            ->call('delete'), ModelNotFoundException::class);

        Livewire::actingAs($this->intruder)->test(ProductIndex::class)
            ->set('productId', $this->product->id)
            ->call('delete')
            ->assertForbidden();

        $this->assertModelExists($this->product);
        $this->assertModelExists($this->product->business);
    }

    public function test_vendor_cannot_edit_business_of_another_vendor(): void
    {
        Livewire::actingAs($this->intruder)
            ->test(FormEdit::class, ['business' => $this->product->business])
            ->assertForbidden();

        $this->assertNotSame($this->intruder->id, $this->product->business->fresh()->user_id);
    }

    public function test_product_is_created_with_stored_image_only_in_own_business(): void
    {
        Storage::fake('public');
        $ownBusiness = Business::factory()->create(['user_id' => $this->intruder->id]);

        // En un negocio ajeno falla la validación
        Livewire::actingAs($this->intruder)->test(ProductCreate::class)
            ->set('form.name', 'Taco')
            ->set('form.description', 'De asada')
            ->set('form.price', 25)
            ->set('form.quantity', 10)
            ->set('form.status', 'available')
            ->set('form.image_path', $this->fakeImage())
            ->set('form.business_id', $this->product->business_id)
            ->call('store')
            ->assertHasErrors('form.business_id');

        // En el propio se crea y la imagen queda guardada en el disco público
        Livewire::actingAs($this->intruder)->test(ProductCreate::class)
            ->set('form.name', 'Taco')
            ->set('form.description', 'De asada')
            ->set('form.price', 25)
            ->set('form.quantity', 10)
            ->set('form.status', 'available')
            ->set('form.image_path', $this->fakeImage())
            ->set('form.business_id', $ownBusiness->id)
            ->call('store')
            ->assertHasNoErrors();

        $product = Product::where('business_id', $ownBusiness->id)->firstOrFail();
        $this->assertStringStartsWith('product/images/', $product->image_path);
        Storage::disk('public')->assertExists($product->image_path);
    }

    // PNG real de 1x1 px (no depende de la extensión GD)
    private function fakeImage(): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');

        return UploadedFile::fake()->createWithContent('taco.png', $png);
    }

    public function test_hidden_business_is_not_visible_in_the_store(): void
    {
        $business = Business::factory()->create(['status' => 'inactive']);
        $client = User::factory()->create();

        $this->actingAs($client)->get(route('store.business', $business))->assertNotFound();
    }
}
