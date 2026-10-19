<x-app-layout>
    <x-slot name="header">
        <x-admin.header title="Nuevo negocio" description="Completa los datos para que tu negocio aparezca en la tienda."
            :back="route('business.index')" back-label="Negocios" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-10">
        @livewire('business.form-create')
    </div>
</x-app-layout>
