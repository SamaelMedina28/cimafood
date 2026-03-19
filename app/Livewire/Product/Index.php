<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Business;
use Livewire\Component;
use Livewire\WithPagination;
use PDO;

class Index extends Component
{
    use WithPagination;

    public function paginationView()
    {
        return 'components.pagination';
    }

    public $businessId = '';
    public $productName = '';

    public function updatingProductName()
    {
        $this->resetPage();
    }

    public function updatingBusinessId()
    {
        $this->resetPage();
    }

    public function render()
    {
        $products = Product::with('business')
            ->when($this->businessId, fn($q) => $q->where('business_id', $this->businessId))
            ->when($this->productName, fn($q) => $q->where('name', 'like', '%' . $this->productName . '%'))
            ->paginate(10);

        $businesses = Business::all();

        return view('livewire.product.index', compact('products', 'businesses'));
    }
}
