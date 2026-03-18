<?php

namespace App\Livewire\Forms\Business;

use \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Business;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UpdateBusiness extends Form
{
    public $name;
    public $description;
    public $phone;
    public $logo;
    public $banner;
    public $open_time = '08:00';
    public $close_time = '18:00';
    public $status;


    // Funciones de rules
    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'phone' => 'required|string|max:255',
            'open_time' => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i',
            'status' => 'required|in:active,inactive',
        ];

        if ($this->logo instanceof TemporaryUploadedFile) {
            $rules['logo'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        if ($this->banner instanceof TemporaryUploadedFile) {
            $rules['banner'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }

        return $rules;
    }

    public function update(Business $business)
    {
        $this->validate();
        $dataToUpdate = [
            'name' => $this->name,
            'description' => $this->description,
            'phone' => $this->phone,
            'open_time' => $this->open_time,
            'close_time' => $this->close_time,
            'status' => $this->status,
            'user_id' => Auth::user()->id,
        ];

        if ($this->logo instanceof TemporaryUploadedFile) {
            $dataToUpdate['logo'] = $this->logo->store('business/logo', 'public');
        }

        if ($this->banner instanceof TemporaryUploadedFile) {
            $dataToUpdate['banner'] = $this->banner->store('business/banner', 'public');
        }

        $business->update($dataToUpdate);
    }

    public function setForm(Business $business)
    {
        $this->name = $business->name;
        $this->description = $business->description;
        $this->phone = $business->phone;
        $this->logo = $business->logo;
        $this->banner = $business->banner;
        $this->open_time = $business->open_time;
        $this->close_time = $business->close_time;
        $this->status = $business->status;
    }
}
