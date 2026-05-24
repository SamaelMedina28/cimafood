<?php

namespace App\Livewire\Client;

use Livewire\Component;
use App\Models\Business;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{

    public $businesses;
    public $products;
    public $featuredProducts;
    public String $filter = 'todos';

    public function mount() {}

    public function setFilter(String $filter)
    {
        $this->filter = $filter;
        $this->render();
    }

    public function render()
    {
        $horaActual = date('H:i');

        switch ($this->filter) {
            case 'abierto':
                $this->businesses = Business::where('status', 'active')
                    ->where('open_time', '<=', $horaActual)
                    ->where('close_time', '>=', $horaActual)
                    ->limit(10)
                    ->get();
                $this->products = collect();
                break;

            case 'populares':
                $this->businesses = Business::where('status', 'active')
                    ->withCount('orders')
                    ->orderBy('orders_count', 'desc')
                    ->limit(10)
                    ->get();
                $this->products = collect();
                break;

            case 'economico':
                $this->businesses = collect();
                $this->products = Product::where('status', 'available')
                    ->where('price', '<', 50)
                    ->with('business')
                    ->limit(20)
                    ->get();
                break;
            case 'todos':
            default:
                $this->businesses = Business::where('status', 'active')->limit(10)->get();
                $this->products = collect();
                break;
        }

        // Productos más vendidos (top 20) basados en cantidad vendida
        $this->featuredProducts = Product::where('status', 'available')
            ->with('business')
            ->withCount(['orders as total_sold' => function ($query) {
                $query->select(DB::raw('SUM(order_product.quantity)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->limit(20)
            ->get();

        return view('livewire.client.dashboard');
    }
}
