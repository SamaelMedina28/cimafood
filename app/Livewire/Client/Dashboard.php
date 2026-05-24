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
        // TODO: implementar vistas para filtros de productos y de negocios
        $this->filter = $filter;
        if ($filter == 'abiertos'){
            $horaActual = date('H:i:s');
            $this->businesses = Business::where('open_time', '<=', $horaActual)->where('close_time', '>=', $horaActual)->limit(10)->get();
        }
    }


    public function render()
    {
        // TODO: implementar filtrosn para productos mas pedidos ultimamente siguiendo un algoritmo tomando como referencia los ultimos 30 pedidos hechos en la plataforma
        $this->businesses = Business::where('status', 'active')->limit(10)->get();
        $this->products = Product::where('status', 'available')->limit(10)->get();
        $this->featuredProducts = Product::where('status', 'available')->limit(10)->get();
        return view('livewire.client.dashboard');
    }
}
