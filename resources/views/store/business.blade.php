<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('store') }}" wire:navigate
                class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900 truncate px-4">{{ $business->name }}</h1>
            {{-- Botón de carrito con badge reactivo + toast al agregar --}}
            <button class="relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200"
                x-data="{
                    count: 0,
                    added: false,
                    init() {
                        try {
                            let c = JSON.parse(localStorage.getItem('cimafood_global_cart') || '[]');
                            this.count = c.reduce((s, i) => s + i.quantity, 0);
                        } catch (e) {}
                        window.addEventListener('cart-count-updated', e => this.count = e.detail.count);
                        window.addEventListener('add-to-cart', () => {
                            this.added = true;
                            setTimeout(() => this.added = false, 2000);
                        });
                    }
                }" x-init="init()"
                @click="window.dispatchEvent(new CustomEvent('open-cart'))">
                {{-- Ícono del carrito, se convierte en check brevemente al agregar --}}
                <div x-show="!added" class="text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div x-show="added" x-transition class="text-emerald-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                {{-- Badge con cantidad --}}
                <span x-show="count > 0" x-text="count" x-transition
                    class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
            </button>
        </div>
    </x-slot>

    {{-- El carrito es global (definido en el layout). Aquí solo pasamos contexto del negocio. --}}
    <div class="pb-24 bg-gray-50 min-h-screen">
        {{-- Banner --}}
        <div class="relative w-full h-48 sm:h-64 bg-gray-300">
            @if ($business->banner)
                <img src="{{ str_starts_with($business->banner, 'http') ? $business->banner : asset('storage/' . $business->banner) }}"
                    alt="Banner {{ $business->name }}" class="w-full h-full object-cover">
            @else
                <div
                    class="w-full h-full flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500">
                    <span class="text-white/50 text-4xl font-bold">Sin Portada</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        {{-- Info del Negocio --}}
        <div class="px-4 relative z-10 -mt-16 sm:-mt-20 max-w-5xl mx-auto">
            <div
                class="bg-white rounded-2xl shadow-md p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-end gap-4">
                {{-- Logo --}}
                <div
                    class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white flex-shrink-0">
                    @if ($business->logo)
                        <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                            alt="{{ $business->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                            <span
                                class="text-4xl font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex-grow text-center sm:text-left mt-2 sm:mt-0">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $business->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                        {{ $business->description ?? 'Sin descripción disponible.' }}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-4 mt-3">
                        {{--  Horario de Atención --}}
                        @php
                            // Sacar la hora actual
                            $horaActual = date('H:i');
                            $isOpen = $horaActual >= $business->open_time && $horaActual <= $business->close_time;
                        @endphp
                        <div
                            class="text-sm font-medium text-gray-700 border px-3 py-1 rounded-full {{ $isOpen ? 'bg-green-100 border-green-400' : 'bg-red-100 border-red-400' }}">
                            {{ $business->open_time }} - {{ $business->close_time }}
                        </div>
                        <div
                            class="flex items-center text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500 mr-1">★</span>
                            {{ number_format($business->averageRating() ?? 0, 1) }} ({{ $business->totalReviews() }}+)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menú de Productos --}}
        <div class="mt-8 px-4 max-w-5xl mx-auto">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">🍽️</span> Nuestros Productos
            </h2>

            @if ($business->products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($business->products as $product)
                        <div
                            class="bg-white rounded-2xl p-3 flex gap-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                            {{-- Imagen del producto --}}
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if ($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                        alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">🍲</div>
                                @endif
                            </div>

                            {{-- Info del producto --}}
                            <div class="flex flex-col justify-between flex-grow">
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
                                        class="text-lg font-extrabold text-green-600">${{ number_format($product->price, 2) }}</span>
                                    <div class="flex items-center gap-2">
                                        <button x-data="{
                                            isFavorite: {{ in_array($product->id, $favorites) ? 'true' : 'false' }},
                                            loading: false,
                                            toggle() {
                                                if (this.loading) return;
                                                this.loading = true;
                                                fetch('{{ route('store.favorites.toggle', $product->id) }}', {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Accept': 'application/json'
                                                        }
                                                    })
                                                    .then(res => res.json())
                                                    .then(data => {
                                                        this.isFavorite = data.isFavorite;
                                                    })
                                                    .catch(err => console.error(err))
                                                    .finally(() => {
                                                        this.loading = false;
                                                    });
                                            }
                                        }" @click="toggle()"
                                            class="w-8 h-8 rounded-full bg-yellow-100 hover:bg-yellow-200 text-yellow-600 flex items-center justify-center shadow-sm transition-colors transform active:scale-95"
                                            :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                            <svg class="w-5 h-5 transition-transform duration-200"
                                                :class="{ 'scale-110': isFavorite }"
                                                :fill="isFavorite ? 'currentColor' : 'none'" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                        </button>
                                        <button
                                            @click="$dispatch('add-to-cart', {
                                                id: {{ $product->id }},
                                                name: '{{ addslashes($product->name) }}',
                                                price: {{ $product->price }},
                                                image: '{{ $product->image_path ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path)) : '' }}',
                                                businessId: {{ $business->id }},
                                                businessName: '{{ addslashes($business->name) }}',
                                                quantity: {{ $product->quantity ?? 0 }}
                                            })"
                                            class="w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 flex items-center justify-center shadow-sm transition-colors transform active:scale-95 {{ isset($product->quantity) && $product->quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            :disabled="{{ isset($product->quantity) && $product->quantity <= 0 ? 'true' : 'false' }}">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-700">Aún no hay productos</h3>
                    <p class="text-sm text-gray-500 mt-1">Este negocio pronto agregará platillos deliciosos.</p>
                </div>
            @endif
        </div>

    </div>
</x-client-layout>
