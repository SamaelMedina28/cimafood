<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Livewire\Component;

class Index extends Component
{
    public $products;
    public $searchByBusiness = '';

    public function mount()
    {
        $this->products = Product::all();
    }

    public function searchByBusiness()
    {
        $this->products = Product::where('business_id', $this->searchByBusiness)->get();
    }
    public function render()
    {
        return view('livewire.product.index');
    }
}
