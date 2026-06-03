<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    @php
        $filters = [
            ['id' => 'todos', 'label' => '🏠 Todos'],
            ['id' => 'abierto', 'label' => '🟢 Abierto ahora'],
            ['id' => 'populares', 'label' => '🔥 Más populares'],
            ['id' => 'economico', 'label' => '💰 Menos de $50'],
            ['id' => 'rapido', 'label' => '⚡ Entrega rápida'],
        ];
    @endphp
    {{-- ===================== FILTROS (PILLS) ===================== --}}
    <div class="flex overflow-x-auto gap-2 py-4 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
        @foreach ($filters as $filterItem)
            <button wire:click="setFilter('{{ $filterItem['id'] }}')"
                class="flex-shrink-0 px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 whitespace-nowrap shadow-sm {{ $filterItem['id'] === $filter ? 'bg-gray-900 text-white' : 'bg-white text-gray-700 border border-gray-200 hover:border-gray-400' }}">
                {{ $filterItem['label'] }}
            </button>
        @endforeach
    </div>

    {{-- ===================== NEGOCIOS ===================== --}}
    @if ($filter !== 'economico')
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3 px-1">
                <h2 class="text-base font-bold text-gray-900">
                    @if ($filter === 'abierto')
                        🟢 Negocios Abiertos
                    @elseif ($filter === 'populares')
                        🔥 Negocios Populares
                    @elseif ($filter === 'rapido')
                        ⚡ Entrega Rápida
                    @else
                        Negocios
                    @endif
                </h2>
                @if ($filter === 'todos')
                    <a href="{{ route('store.businesses') }}" wire:navigate
                        class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos</a>
                @endif
            </div>
            @if ($businesses->count() > 0)
                <div class="flex overflow-x-auto gap-3 pb-2 px-1"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach ($businesses as $business)
                        <a href="{{ route('store.business', $business->id) }}" wire:navigate
                            class="flex flex-col items-center flex-shrink-0 snap-center group" style="width: 72px;">
                            <div
                                class="w-16 h-16 rounded-full overflow-hidden border border-gray-200 shadow-sm bg-white group-hover:shadow-md transition-shadow duration-200">
                                @if ($business->logo)
                                    <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                                        alt="{{ $business->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-emerald-50 flex items-center justify-center">
                                        <span
                                            class="text-xl font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                            </div>
                            <span
                                class="mt-1.5 text-xs text-gray-700 font-medium text-center leading-tight w-full truncate">
                                {{ $business->name }}
                            </span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 border-dashed">
                    @if ($filter === 'abierto')
                        <p class="text-sm text-gray-500">No hay negocios abiertos a esta hora 😴</p>
                    @elseif ($filter === 'populares')
                        <p class="text-sm text-gray-500">No hay negocios populares aún</p>
                    @elseif ($filter === 'rapido')
                        <p class="text-sm text-gray-500">No hay negocios con entrega rápida</p>
                    @else
                        <p class="text-sm text-gray-500">No hay negocios disponibles</p>
                    @endif
                </div>
            @endif
        </div>
    @endif

    {{-- ===================== PRODUCTOS ECONÓMICOS ===================== --}}
    @if ($filter === 'economico')
        <div class="mb-6">
            <div class="flex items-center justify-between mb-3 px-1">
                <h2 class="text-base font-bold text-gray-900">💰 Productos menos de $50</h2>
            </div>
            @if ($products->count() > 0)
                <div class="flex overflow-x-auto gap-4 pb-2 px-1"
                    style="scrollbar-width: none; -ms-overflow-style: none;">
                    @foreach ($products as $product)
                        <div
                            class="flex-shrink-0 w-44 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow duration-200">
                            {{-- Imagen --}}
                            <div class="relative w-full h-28 bg-gray-100 overflow-hidden">
                                @if ($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-emerald-50">
                                        🍽️
                                    </div>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="p-3">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $product->name }}
                                    </h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                    {{-- Display product stock quantity --}}
                                    <p
                                        class="text-xs mt-1 font-semibold {{ $product->quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                                        {{ $product->quantity > 0 ? $product->quantity . ' disponible' . ($product->quantity !== 1 ? 's' : '') : 'Sin stock' }}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span
                                        class="text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                    <button
                                        @click="$dispatch('add-to-cart', {
                                            id: {{ $product->id }},
                                            name: '{{ addslashes($product->name) }}',
                                            price: {{ $product->price }},
                                            image: '{{ $product->image_path ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path)) : '' }}',
                                            businessId: {{ $product->business->id }},
                                            businessName: '{{ addslashes($product->business->name) }}',
                                            quantity: {{ $product->quantity ?? 0 }}
                                        })"
                                        class="w-7 h-7 rounded-full bg-green-600 hover:bg-green-700 flex items-center justify-center transition-colors duration-200 shadow-sm {{ isset($product->quantity) && $product->quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        :disabled="{{ isset($product->quantity) && $product->quantity <= 0 ? 'true' : 'false' }}">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 border-dashed">
                    <p class="text-sm text-gray-500">No hay productos económicos disponibles 💰</p>
                </div>
            @endif
        </div>
    @endif

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
            @foreach ($categories as $cat)
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
            <h2 class="text-base font-bold text-gray-900 ml-2"> Más vendidos</h2>
            <a href="{{ route('store.products') }}" wire:navigate
                class="text-sm font-medium text-emerald-600 hover:text-emerald-700">Ver todos</a>
        </div>
        @if ($featuredProducts->count() > 0)
            <div class="flex overflow-x-auto gap-4 pb-2 px-1" style="scrollbar-width: none; -ms-overflow-style: none;">
                @foreach ($featuredProducts as $product)
                    <div
                        class="flex-shrink-0 w-44 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden group hover:shadow-md transition-shadow duration-200">

                        {{-- Imagen --}}
                        <div class="relative w-full h-28 bg-gray-100 overflow-hidden">
                            @if ($product->image_path)
                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl bg-emerald-50">🍽️
                                </div>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="p-3">
                             <div>
                                   <h3 class="text-base font-bold text-gray-900 leading-  tight">{{ $product->name }}
                                     </h3>
                                 <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                 <p class="text-xs text-gray-400 mt-2">{{ $product->business->name ?? '' }}</p>
                                {{-- Display product stock quantity --}}
                                     <p
                                       class="text-xs mt-1 font-semibold {{ $product->quantity > 0 ? 'text-green-600' : 'text-red-500' }}">
                                      {{ $product->quantity > 0 ? $product->quantity . ' disponible' . ($product->quantity !== 1 ?    's' : '') : 'Sin stock' }}
                                     </p>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <span
                                    class="text-sm font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
                                <button
                                    @click="$dispatch('add-to-cart', {
                                        id: {{ $product->id }},
                                        name: '{{ addslashes($product->name) }}',
                                        price: {{ $product->price }},
                                        image: '{{ $product->image_path ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path)) : '' }}',
                                        businessId: {{ $product->business->id }},
                                        businessName: '{{ addslashes($product->business->name) }}',
                                        quantity: {{ $product->quantity ?? 0 }}
                                    })
                                    "class="w-7 h-7 rounded-full bg-green-600 hover:bg-green-700 flex items-center justify-center transition-colors duration-200 shadow-sm {{ isset($product->quantity) && $product->quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                    :disabled="{{ isset($product->quantity) && $product->quantity <= 0 ? 'true' : 'false' }}">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-6 text-center border border-gray-100 border-dashed">
                <p class="text-sm text-gray-500">Aún no hay productos vendidos 🔥</p>
            </div>
        @endif
    </div>
</div>
