<form wire:submit="update">
    @include('livewire.business.partials.form-fields', ['form' => $updateBusiness, 'prefix' => 'updateBusiness', 'submitAction' => 'update', 'submitLabel' => 'Guardar cambios'])
</form>
