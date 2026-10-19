<x-app-layout>
    <x-slot name="header">
        <x-admin.header :title="$product->name" description="Edita los datos del producto."
            :back="route('product.index')" back-label="Productos" />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-10">
        @livewire('product.edit', ['product' => $product])
    </div>
</x-app-layout>
