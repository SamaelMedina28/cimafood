<x-app-layout>
    <x-slot name="header">
        <x-admin.header title="Productos" description="El menú de todos tus negocios.">
            <x-slot name="actions">
                <a href="{{ route('product.create') }}" wire:navigate
                    class="inline-flex items-center gap-1.5 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                    <x-icon name="plus" class="size-4" />
                    Nuevo producto
                </a>
            </x-slot>
        </x-admin.header>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-8">
        @livewire('product.index')
    </div>
</x-app-layout>
