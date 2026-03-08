<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crear Nuevo Negocio') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <p class="mb-4 text-sm text-gray-600">
                        Completa el formulario para registrar un nuevo negocio en el sistema.
                    </p>

                    @livewire('business.form-create')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
