<?php

namespace App\Livewire\Forms\Product;

use App\Models\Product;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreProducts extends Form
{
    //
    #[Validate('required|string|max:255')]
    public $name;
    #[Validate('required|string|max:255')]
    public $description;
    #[Validate('required|numeric|min:0')]
    public $price;
    #[Validate('required|image|max:10240')]
    public $image_path;
    #[Validate('required|integer|min:0')]
    public $quantity;
    #[Validate('required|in:available,unavailable')]
    public $status;
    #[Validate('required|exists:businesses,id')]
    public $business_id;

    public function store()
    {
        $this->validate();
        Product::create($this->all());
        $this->reset();
    }
}
