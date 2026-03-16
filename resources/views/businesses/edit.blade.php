<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-2">
      <a href="{{ route('business.index') }}" wire:navigate
        class="inline-flex items-center gap-1.5 px-3 text-sm font-medium text-slate-600 bg-white rounded-full">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver
      </a>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Editar negocio: <i>{{ $business->name }}</i>
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="px-4 py-5 sm:p-6">
          <p class="mb-4 text-sm text-gray-600">
            Completa el formulario para editar tu negocio en el sistema.
          </p>

          @livewire('business.form-create', ['business' => $business])
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
