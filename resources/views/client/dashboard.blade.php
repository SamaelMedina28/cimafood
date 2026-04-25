<x-client-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between w-full">
      <div class="flex items-center gap-2" x-data="{ open: false }">
        <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>
        <input x-show="open"
          x-transition:enter="transition ease-out duration-200"
          x-transition:enter-start="opacity-0 w-0"
          x-transition:enter-end="opacity-100 w-48"
          x-transition:leave="transition ease-in duration-150"
          x-transition:leave-start="opacity-100 w-48"
          x-transition:leave-end="opacity-0 w-0"
          type="text" placeholder="Buscar..."
          class="w-48 rounded-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm overflow-hidden" />
      </div>
      <button class="relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
      </button>
    </div>
  </x-slot>

  <div class="pb-10">

    {{-- ===================== BANNER / CARRUSEL ===================== --}}
    <div x-data="{
        current: 0,
        total: 3,
        autoplay: null,
        start() {
            this.autoplay = setInterval(() => {
                this.current = (this.current + 1) % this.total
            }, 4000)
        }
    }" x-init="start()" class="relative w-full overflow-hidden" style="height: 180px;">

      {{-- Slide 1 --}}
      <div x-show="current === 0" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
        class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-400 flex items-center px-8"
        style="background-image: url('{{ asset('promotionals/Hamburgesa.png') }}'); background-size: cover; background-position: center;">
        <div>
          <span class="bg-white text-emerald-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Oferta del día</span>
          <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">20% off en<br>tu primer pedido</h3>
          <p class="text-emerald-100 text-sm mt-1">Usa el código <span class="font-bold">BIENVENIDO</span></p>
        </div>
        <div class="ml-auto text-6xl">🍔</div>
      </div>

      {{-- Slide 2 --}}
      <div x-show="current === 1" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
        class="absolute inset-0 bg-gradient-to-r from-orange-500 to-yellow-400 flex items-center px-8"
        style="background-image: url('{{ asset('promotionals/Tacos.png') }}'); background-size: cover; background-position: center;">
        <div>
          <span class="bg-white text-orange-500 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Nuevo</span>
          <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">Combos desde<br>$45 pesos</h3>
          <p class="text-orange-100 text-sm mt-1">Disponibles todo el día</p>
        </div>
        <div class="ml-auto text-6xl">🌮</div>
      </div>

      {{-- Slide 3 --}}
      <div x-show="current === 2" x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full"
        class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-500 flex items-center px-8"
        style="background-image: url('{{ asset('promotionals/Bobbas.png') }}'); background-size: cover; background-position: center;">
        <div>
          <span class="bg-white text-purple-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Popular</span>
          <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">Postres y<br>bebidas frías</h3>
          <p class="text-purple-100 text-sm mt-1">Perfectos para el calor</p>
        </div>
        <div class="ml-auto text-6xl">🧋</div>
      </div>

      {{-- Dots --}}
      <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
        <template x-for="i in total" :key="i">
          <button @click="current = i - 1"
            :class="current === i - 1 ? 'bg-white w-4' : 'bg-white/50 w-1.5'"
            class="h-1.5 rounded-full transition-all duration-300"></button>
        </template>
      </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

      {{-- ===================== FILTROS (PILLS) ===================== --}}
      <div class="flex overflow-x-auto gap-2 py-4 px-1" style="scrollbar-width: none; -ms-overflow-style: none;"
        x-data="{ active: 'todos' }">
        @foreach([
  ['id' => 'todos', 'label' => '🏠 Todos'],
  ['id' => 'abierto', 'label' => '🟢 Abierto ahora'],
  ['id' => 'populares', 'label' => '🔥 Más populares'],
  ['id' => 'economico', 'label' => '💰 Menos de $50'],
  ['id' => 'rapido', 'label' => '⚡ Entrega rápida'],
] as $filter)
          <button
            @click="active = '{{ $filter['id'] }}'"
            :class="active === '{{ $filter['id'] }}' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-400'"
            class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap shadow-sm">
            {{ $filter['label'] }}
          </button>
        @endforeach
      </div>

      {{-- ===================== CATEGORÍAS ===================== --}}
      <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
          <h2 class="text-base font-bold text-gray-900">¿Qué se te antoja?</h2>
        </div>
        <div class="flex overflow-x-auto gap-3 pb-2 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
          @foreach([
  ['emoji' => '🍔', 'label' => 'Hamburguesas'],
  ['emoji' => '🌮', 'label' => 'Tacos'],
  ['emoji' => '🍕', 'label' => 'Pizza'],
  ['emoji' => '🌯', 'label' => 'Burritos'],
  ['emoji' => '🥗', 'label' => 'Ensaladas'],
  ['emoji' => '🧋', 'label' => 'Bebidas'],
  ['emoji' => '🍩', 'label' => 'Postres'],
  ['emoji' => '🍜', 'label' => 'Sopas'],
] as $cat)
            <a href="#" class="flex flex-col items-center flex-shrink-0 group" style="width: 68px;">
              <div class="w-14 h-14 rounded-2xl bg-gray-100 group-hover:bg-emerald-50 flex items-center justify-center text-2xl transition-colors duration-200 shadow-sm">
                {{ $cat['emoji'] }}
              </div>
              <span class="mt-1.5 text-xs text-gray-600 font-medium text-center leading-tight">{{ $cat['label'] }}</span>
            </a>
          @endforeach
        </div>
      </div>

      {{-- ===================== NEGOCIOS ===================== --}}
      <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
          <h2 class="text-base font-bold text-gray-900">Negocios</h2>
          <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos</a>
        </div>
        <div class="flex overflow-x-auto gap-3 pb-2 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
          @foreach($businesses as $business)
            <a href="#" class="flex flex-col items-center flex-shrink-0 snap-center group" style="width: 72px;">
              <div class="w-16 h-16 rounded-full overflow-hidden border border-gray-200 shadow-sm bg-white group-hover:shadow-md transition-shadow duration-200">
                @if($business->logo)
                  <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                    alt="{{ $business->name }}" class="w-full h-full object-cover">
                @else
                  <div class="w-full h-full bg-emerald-50 flex items-center justify-center">
                    <span class="text-xl font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                  </div>
                @endif
              </div>
              <span class="mt-1.5 text-xs text-gray-700 font-medium text-center leading-tight w-full truncate">
                {{ $business->name }}
              </span>
            </a>
          @endforeach
        </div>
      </div>

      {{-- ===================== PRODUCTOS DESTACADOS ===================== --}}
      <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
          <h2 class="text-base font-bold text-gray-900">🔥 Más vendidos</h2>
          <a href="#" class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos</a>
        </div>
        <div class="flex overflow-x-auto gap-4 pb-2 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
          @foreach($featuredProducts as $product)
            <div class="flex-shrink-0 w-44 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow duration-200">

              {{-- Imagen --}}
              <div class="relative w-full h-28 bg-gray-100 overflow-hidden">
                @if($product->image)
                  <img src="{{ str_starts_with($product->image, 'http') ? $product->image : asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                  <div class="w-full h-full flex items-center justify-center text-4xl bg-emerald-50">🍽️</div>
                @endif
              </div>

              {{-- Info --}}
              <div class="p-3">
                <p class="text-sm font-semibold text-gray-800 truncate">{{ $product->name }}</p>
                <p class="text-xs text-gray-400 truncate mt-0.5">{{ $product->business->name ?? '' }}</p>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                  <button class="w-7 h-7 rounded-full bg-emerald-500 hover:bg-emerald-600 flex items-center justify-center transition-colors duration-200 shadow-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                  </button>
                </div>
              </div>

            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</x-client-layout>
