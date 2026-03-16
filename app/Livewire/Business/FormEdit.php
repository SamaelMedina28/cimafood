<?php

namespace App\Livewire\Business;

use Livewire\Component;

class FormEdit extends Component
{
    public $business;

    public function mount($business)
    {
        $this->business = $business;
    }

    public function render()
    {
        return view('livewire.business.form-edit');
    }
}
