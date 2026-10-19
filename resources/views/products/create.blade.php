<x-app-layout>
    <x-slot name="header">
        <x-admin.header title="Nuevo producto" description="Agrégalo al menú de uno de tus negocios."
            :back="route('product.index')" back-label="Productos" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-10">
        @livewire('product.create')
    </div>
</x-app-layout>
