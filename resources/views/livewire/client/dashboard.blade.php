<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @php
        $filters = [
            ['id' => 'todos', 'label' => '🏠 Todos'],
            ['id' => 'abierto', 'label' => '🟢 Abierto ahora'],
            ['id' => 'populares', 'label' => '🔥 Más populares'],
            ['id' => 'economico', 'label' => '💰 Menos de $50'],
            ['id' => 'rapido', 'label' => '⚡ Entrega rápida'],
        ]
      @endphp
    {{-- ===================== FILTROS (PILLS) ===================== --}}
    <div class="flex overflow-x-auto gap-2 py-4 px-1" style="scrollbar-width: none; -ms-overflow-style: none;"
        x-data="{ active: 'todos' }">
        @foreach($filters as $filter)
            <button @click="active = '{{ $filter['id'] }}'"
                :class="active === '{{ $filter['id'] }}' ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-400'"
                class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap shadow-sm">
                {{ $filter['label'] }}
            </button>
        @endforeach
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
                    <div
                        class="w-16 h-16 rounded-full overflow-hidden border border-gray-200 shadow-sm bg-white group-hover:shadow-md transition-shadow duration-200">
                        @if($business->logo)
                            <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                                alt="{{ $business->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-emerald-50 flex items-center justify-center">
                                <span
                                    class="text-xl font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
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

    {{-- ===================== CATEGORÍAS ===================== --}}
    <div class="mb-6">
        <div class="flex items-center justify-between mb-3 px-1">
            <h2 class="text-base font-bold text-gray-900">¿Qué se te antoja?</h2>
        </div>
        @php
            $categories = [
                ['emoji' => '🍔', 'label' => 'Hamburguesas'],
                ['emoji' => '🌮', 'label' => 'Tacos'],
                ['emoji' => '🍕', 'label' => 'Pizza'],
                ['emoji' => '🌯', 'label' => 'Burritos'],
                ['emoji' => '🥗', 'label' => 'Ensaladas'],
                ['emoji' => '🧋', 'label' => 'Bebidas'],
                ['emoji' => '🍩', 'label' => 'Postres'],
                ['emoji' => '🍜', 'label' => 'Sopas'],
            ];
        @endphp
        <div class="flex overflow-x-auto gap-3 pb-2 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            @foreach($categories as $cat)
                <a href="#" class="flex flex-col items-center flex-shrink-0 group" style="width: 68px;">
                    <div
                        class="w-14 h-14 rounded-2xl bg-gray-100 group-hover:bg-emerald-50 flex items-center justify-center text-2xl transition-colors duration-200 shadow-sm">
                        {{ $cat['emoji'] }}
                    </div>
                    <span
                        class="mt-1.5 text-xs text-gray-600 font-medium text-center leading-tight">{{ $cat['label'] }}</span>
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
                <div
                    class="flex-shrink-0 w-44 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow duration-200">

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
                            <button
                                class="w-7 h-7 rounded-full bg-emerald-500 hover:bg-emerald-600 flex items-center justify-center transition-colors duration-200 shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    </div>

</div>
