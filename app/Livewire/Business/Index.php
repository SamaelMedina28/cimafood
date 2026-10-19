<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $businesses;
    public $isOpen = false;
    public $businessIdToDelete;

    public function mount()
    {
        $this->businesses = Auth::user()->businesses;
    }

    public function delete()
    {
        if ($this->businessIdToDelete) {
            $business = Auth::user()->businesses()->with('products')->findOrFail($this->businessIdToDelete);
            $this->authorize('manage', $business);

            // La base de datos borra en cascada productos y pedidos, pero no sus imágenes
            Business::deleteImage($business->logo);
            Business::deleteImage($business->banner);
            $business->products->each(fn($product) => Business::deleteImage($product->image_path));

            $business->delete();
            $this->businesses = Auth::user()->businesses;
            $this->businessIdToDelete = null;
            $this->isOpen = false;
        }
    }
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->businessIdToDelete = $id;
    }

    public function render()
    {
        return view('livewire.business.index');
    }
}
