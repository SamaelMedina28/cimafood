<form wire:submit="store">
    @include('livewire.business.partials.form-fields', ['form' => $business, 'prefix' => 'business', 'submitAction' => 'store', 'submitLabel' => 'Crear negocio'])
</form>
