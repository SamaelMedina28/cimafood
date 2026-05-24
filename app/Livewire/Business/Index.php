<?php

namespace App\Livewire\Business;

use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $businesses;
    public $isOpen = false;
    public $businessIdToDelete;

    public function mount()
    {
        $this->businesses = Auth::user()->businesses;
    }

    public function delete()
    {
        if ($this->businessIdToDelete) {
            Business::findOrFail($this->businessIdToDelete)->delete();
            $this->businesses = Auth::user()->businesses;
            $this->businessIdToDelete = null;
            $this->isOpen = false;
        }
    }
    public function openModal($id)
    {
        $this->isOpen = true;
        $this->businessIdToDelete = $id;
    }

    public function render()
    {
        return view('livewire.business.index');
    }
}
