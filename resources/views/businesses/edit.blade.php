<x-app-layout>
    <x-slot name="header">
        <x-admin.header :title="$business->name" description="Edita la información de tu negocio."
            :back="route('business.show', $business)" back-label="Volver al negocio" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-10">
        @livewire('business.form-edit', ['business' => $business])
    </div>
</x-app-layout>
