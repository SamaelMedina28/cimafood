<?php

namespace App\Livewire\Forms\Business;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class StoreBusiness extends Form
{
    #[Validate('required|string|min:3|max:255')]
    public $name;
    #[Validate('required|string|min:3|max:255')]
    public $description;
    #[Validate('required|string|min:3|max:255')]
    public $phone;
    #[Validate('required|image|max:2048')]
    public $logo;
    #[Validate('required|image|max:2048')]
    public $banner;
    #[Validate('required|date_format:H:i')]
    public $open_time = '08:00';
    #[Validate('required|date_format:H:i')]
    public $close_time = '18:00';

    public function store()
    {
        $this->validate();
        $business = Business::create([
            'name' => $this->name,
            'description' => $this->description,
            'phone' => $this->phone,
            'logo' => $this->logo->store('business/logo', 'public'),
            'banner' => $this->banner->store('business/banner', 'public'),
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
            'user_id' => Auth::user()->id,
        ]);
    }
}
