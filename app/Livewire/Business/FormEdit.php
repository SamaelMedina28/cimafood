<?php

namespace App\Livewire\Business;

use App\Livewire\Forms\Business\UpdateBusiness;
use Livewire\Component;
use Livewire\WithFileUploads;

class FormEdit extends Component
{
    use WithFileUploads;
    public $business;
    public UpdateBusiness $updateBusiness;


    public function mount($business)
    {
        $this->authorize('manage', $business);
        $this->business = $business;
        $this->updateBusiness->setForm($business);
    }

    public function update()
    {
        $this->authorize('manage', $this->business);
        $this->updateBusiness->update($this->business);
        $this->redirect(route('business.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.business.form-edit');
    }
}
