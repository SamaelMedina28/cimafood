<?php

namespace App\Livewire\Product;

use App\Livewire\Forms\Product\UpdateProducts;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public $product;
    public $businesses;
    public UpdateProducts $form;

    public function mount($product)
    {
        $this->product = $product;
        $this->businesses = Auth::user()->businesses;
        $this->form->setForm($product);
    }

    public function update()
    {
        $this->form->update($this->product);
        $this->redirect(route('product.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.product.edit');
    }
}
