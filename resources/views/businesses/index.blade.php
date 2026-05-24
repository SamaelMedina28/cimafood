<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Businesses') }}
    </h2>
  </x-slot>
  <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-end my-5 px-4 sm:px-0">
        <a href="{{ route('business.create') }}"
          class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition ease-in-out duration-150"
          wire:navigate>
          Agregar negocio

        </a>
      </div>
      @livewire('business.index')
    </div>
  </div>
</x-app-layout>
