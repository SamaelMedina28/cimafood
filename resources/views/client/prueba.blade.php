<x-client-layout>
  <x-slot name="header">
  <div class="flex items-center justify-between w-full">

    {{-- Buscador izquierda --}}
    <div class="flex items-center gap-2" x-data="{ open: false }">
      <button @click="open = !open"
        class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </button>
      <input
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 w-0"
        x-transition:enter-end="opacity-100 w-48"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 w-48"
        x-transition:leave-end="opacity-0 w-0"
        type="text" placeholder="Buscar..."
        class="w-48 rounded-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm overflow-hidden" />
    </div>

    {{-- Carrito derecha --}}
    <button class="relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
      <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
      </svg>
    </button>

  </div>
</x-slot>
  <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-end my-5">
        <a href="{{ route('product.create') }}"
          class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition ease-in-out duration-150"
          wire:navigate>
          Prueba producto

        </a>
      </div>
      {{-- Componentes aqui --}}
    </div>
  </div>
</x-client-layout>
