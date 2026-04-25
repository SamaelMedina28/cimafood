<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Business;
use App\Models\Product;
class Dashboard extends Component
{

    public $businesses;
    public $products;
    public $featuredProducts;
    public $filter = 'todos';

    public function mount()
    {
    }

    public function filterBusiness($filter)
    {
        $this->filter = $filter;
        if ($filter = 'abiertos'){
            $horaActual = date('H:i:s');
            $this->businesses = Business::where('opening_time', '<=', $horaActual)->where('closing_time', '>=', $horaActual)->limit(10)->get();
        }
    }

    public function render()
    {
        $this->businesses = Business::limit(10)->get();
        $this->products = Product::all();
        $this->featuredProducts = Product::all();
        return view('livewire.client.dashboard');
    }
}
