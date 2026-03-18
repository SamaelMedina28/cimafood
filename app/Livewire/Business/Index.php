<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Livewire\Component;

class Index extends Component
{
    public $businesses;
    public $showModal = false;

    public function mount()
    {
        $this->businesses = Business::all();
    }

    public function delete($id)
    {
        $business = Business::findOrFail($id);
        $business->delete();
        $this->businesses = Business::all();
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.business.index');
    }
}
