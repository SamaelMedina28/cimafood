<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
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
    public $isOpen = false;
    public $productId;

    public function openModal($id)
    {
        $this->isOpen = true;
        $this->productId = $id;
    }

    public function delete()
    {
        if ($this->productId) {
            Product::find($this->productId)?->delete();
            $this->isOpen = false;
            $this->reset('productId');
        }
    }

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
            ->where('business_id', Auth::user()->businesses->pluck('id'))
            ->when($this->businessId, fn($q) => $q->where('business_id', $this->businessId))
            ->when($this->productName, fn($q) => $q->where('name', 'like', '%' . $this->productName . '%'))
            ->paginate(10);

        $businesses = Auth::user()->businesses;

        return view('livewire.product.index', compact('products', 'businesses'));
    }
}
