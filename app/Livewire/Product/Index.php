<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Business;
use Livewire\Component;

class Index extends Component
{
    public $products;
    public $businesses;
    public $businessId;
    public $productName;

    public function mount()
    {
        $this->products = Product::all();
        $this->businesses = Business::all();
    }

    public function updated()
    {
        $this->search();
    }

    public function search(){
        $this->products = Product::query()->when($this->businessId, function ($query) {
            return $query->where('business_id', $this->businessId);
        })->when($this->productName, function ($query) {
            return $query->where('name', 'like', '%' . $this->productName . '%');
        })->get();
    }
    public function render()
    {
        return view('livewire.product.index');
    }
}
