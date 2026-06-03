<?php

namespace App\Livewire\Business;

use App\Livewire\Forms\Business\StoreBusiness;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormCreate extends Component
{
    use WithFileUploads;

    public StoreBusiness $business;

    public function render()
    {
        return view('livewire.business.form-create');
    }

    public function store()
    {
        $this->business->store();
        return redirect()->route('business.index');
    }
}
