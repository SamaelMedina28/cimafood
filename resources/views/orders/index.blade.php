<x-app-layout>
    <x-slot name="header">
        <x-admin.header title="Pedidos" description="Todos los pedidos de tus negocios, del más reciente al más antiguo." />
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-8">
        @livewire('business.all-orders')
    </div>
</x-app-layout>
