<?php

namespace App\Livewire\Forms\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Form;

class StoreProducts extends Form
{
    public $name;
    public $description;
    public $price;
    public $image_path;
    public $quantity;
    public $status;
    public $business_id;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'image_path' => 'required|image|max:10240',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            // Solo se pueden crear productos en negocios propios
            'business_id' => ['required', Rule::exists('businesses', 'id')->where('user_id', Auth::id())],
        ];
    }

    public function store()
    {
        $this->validate();

        Product::create([
            ...$this->except('image_path'),
            'image_path' => $this->image_path->store('product/images', 'public'),
        ]);

        $this->reset();
    }
}
