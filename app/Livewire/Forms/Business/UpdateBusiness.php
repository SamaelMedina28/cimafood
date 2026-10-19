<?php

namespace App\Livewire\Forms\Business;

use \Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use App\Models\Business;
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
            $rules['logo'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048';
        }

        if ($this->banner instanceof TemporaryUploadedFile) {
            $rules['banner'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20048';
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
        ];

        if ($this->logo instanceof TemporaryUploadedFile) {
            Business::deleteImage($business->logo);
            $dataToUpdate['logo'] = $this->logo->store('business/logo', 'public');
        }

        if ($this->banner instanceof TemporaryUploadedFile) {
            Business::deleteImage($business->banner);
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
        // La base puede devolver H:i:s; el formulario y la validación usan H:i
        $this->open_time = substr($business->open_time, 0, 5);
        $this->close_time = substr($business->close_time, 0, 5);
        $this->status = $business->status;
    }
}
