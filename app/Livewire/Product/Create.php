<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\StoreProducts;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $businesses;
    public StoreProducts $form;

    public function mount()
    {
        $this->businesses = Auth::user()->businesses;
    }

    public function render()
    {
        return view('livewire.product.create');
    }

    public function store()
    {
        $this->form->store();
        $this->redirect(route('product.index'), true);
    }
}
