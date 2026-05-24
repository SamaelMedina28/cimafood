<?php

namespace App\Livewire\Forms\Product;

use App\Models\Product;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;

class UpdateProducts extends Form
{
    public $name;
    public $description;
    public $price;
    public $image_path;
    public $quantity;
    public $status;
    public $business_id;

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'status' => 'required|in:available,unavailable',
            'business_id' => 'required|exists:businesses,id',
        ];

        if ($this->image_path instanceof TemporaryUploadedFile) {
            $rules['image_path'] = 'required|image|max:10240';
        }

        return $rules;
    }

    public function update(Product $product)
    {
        $this->validate();
        
        $dataToUpdate = [
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'status' => $this->status,
            'business_id' => $this->business_id,
        ];

        if ($this->image_path instanceof TemporaryUploadedFile) {
            $dataToUpdate['image_path'] = $this->image_path->store('product/images', 'public');
        }

        $product->update($dataToUpdate);
    }

    public function setForm(Product $product)
    {
        $this->name = $product->name;
        $this->description = $product->description;
        $this->price = $product->price;
        $this->image_path = $product->image_path;
        $this->quantity = $product->quantity;
        $this->status = $product->status;
        $this->business_id = $product->business_id;
    }
}
