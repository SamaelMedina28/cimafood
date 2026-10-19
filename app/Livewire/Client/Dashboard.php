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
    public String $filter = 'todos';

    public function setFilter(String $filter)
    {
        if (in_array($filter, ['todos', 'abierto', 'populares', 'economico'], true)) {
            $this->filter = $filter;
        }
    }

    public function render()
    {
        $activeBusinesses = fn() => Business::where('status', 'active')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating');
        $availableProducts = fn() => Product::where('status', 'available')
            ->whereHas('business', fn($q) => $q->where('status', 'active'))
            ->with('business');

        $this->businesses = collect();
        $this->products = collect();

        switch ($this->filter) {
            case 'abierto':
                // Se filtra en PHP para respetar horarios que cruzan la medianoche
                $this->businesses = $activeBusinesses()->get()
                    ->filter(fn($business) => $business->isOpen())
                    ->take(10)
                    ->values();
                break;

            case 'populares':
                $this->businesses = $activeBusinesses()
                    ->withCount(['orders' => fn($q) => $q->where('status', '!=', 'cancelled')])
                    ->orderBy('orders_count', 'desc')
                    ->limit(10)
                    ->get();
                break;

            case 'economico':
                $this->products = $availableProducts()
                    ->where('price', '<', 50)
                    ->orderBy('price')
                    ->limit(20)
                    ->get();
                break;

            case 'todos':
            default:
                $this->businesses = $activeBusinesses()->limit(10)->get();
                break;
        }

        // Productos más vendidos (top 20) basados en cantidad vendida
        $this->featuredProducts = $availableProducts()
            ->withSum(['orders as total_sold' => fn($q) => $q->where('status', '!=', 'cancelled')], 'order_product.quantity')
            ->orderByDesc('total_sold')
            ->limit(20)
            ->get();

        return view('livewire.client.dashboard');
    }
}
