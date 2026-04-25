<x-client-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between w-full">

      {{-- Buscador izquierda --}}
      <div class="flex items-center gap-2" x-data="{ open: false }">
        <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
        <input x-show="open" x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 w-0" x-transition:enter-end="opacity-100 w-48"
          x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 w-48"
          x-transition:leave-end="opacity-0 w-0" type="text" placeholder="Buscar..."
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

      {{-- Componentes aqui --}}
      <div class="mb-8 mt-4">
        <div class="flex items-center justify-between mb-3 px-1">
          <h2 class="text-base font-bold text-gray-900">Negocios</h2>
          <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos</a>
        </div>

        <div class="flex overflow-x-auto gap-3 pb-2 snap-x snap-mandatory px-1"
          style="scrollbar-width: none; -ms-overflow-style: none;">
          <style>
            .hide-scrollbar::-webkit-scrollbar {
              display: none;
            }
          </style>

          @foreach($businesses as $business)
            <a href="#" class="flex flex-col items-center flex-shrink-0 snap-center group" style="width: 72px;">

              {{-- Avatar circular estilo Uber Eats --}}
              <div
                class="w-16 h-16 rounded-full overflow-hidden border border-gray-200 shadow-sm bg-white group-hover:shadow-md transition-shadow duration-200">
                @if($business->logo)
                  <img
                    src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                    alt="{{ $business->name }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full bg-emerald-50 flex items-center justify-center">
                    <span class="text-xl font-bold text-emerald-600">
                      {{ strtoupper(substr($business->name, 0, 1)) }}
                    </span>
                  </div>
                @endif
              </div>

              {{-- Nombre --}}
              <span class="mt-1.5 text-xs text-gray-700 font-medium text-center leading-tight w-full truncate">
                {{ $business->name }}
              </span>

            </a>
          @endforeach

        </div>
      </div>

    </div>
  </div>
</x-client-layout>
