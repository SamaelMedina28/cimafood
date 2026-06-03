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
                            setTimeout(() => this.added = false, 1000);
                        });
                    }
                }" x-init="init()"
                @click="window.dispatchEvent(new CustomEvent('open-cart'))">
                {{-- Ícono del carrito, se convierte en check brevemente al agregar --}}
                <div class="text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
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
                    <div class="flex items-center justify-center sm:justify-start gap-3 mt-3">
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
                        <button @click="window.dispatchEvent(new CustomEvent('open-reviews-modal'))"
                            class="flex items-center text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full cursor-pointer shadow-md hover:bg-gray-200 transition-colors">
                            <span class="text-yellow-500 mr-1">★</span>
                            {{ number_format($business->averageRating() ?? 0, 1) }} ({{ $business->totalReviews() }}
                            reseñas)
                        </button>
                        {{-- Botón para dejar reseña --}}
                        @if (auth()->check() && !$hasReviewed)
                            <button @click="window.dispatchEvent(new CustomEvent('open-review-modal'))"
                                class="flex items-center p-1 text-gray-600 bg-gray-100 text-sm font-semibold rounded-full shadow-md hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        @elseif(auth()->check() && $hasReviewed)
                            <button
                                class="flex items-center p-1 text-gray-400 bg-gray-100 text-sm font-semibold rounded-full shadow-md cursor-not-allowed"
                                disabled>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </button>
                        @else
                            <button
                                class="flex items-center p-1 text-gray-600 bg-gray-100 text-sm font-semibold rounded-full shadow-md hover:bg-gray-200 transition-colors"
                                onclick="window.location.href='{{ route('login') }}'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        @endif
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
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5" d="M12 4v16m8-8H4" />
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

    {{-- Modal para dejar reseña --}}
    <div x-data="{
        showModal: false,
        rating: 0,
        comment: '',
        loading: false,
        init() {
            window.addEventListener('open-review-modal', () => {
                this.showModal = true;
            });
        },
        submitReview() {
            if (this.rating === 0) {
                alert('Por favor selecciona una calificación');
                return;
            }
            this.loading = true;
            fetch('{{ route('store.reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        business_id: {{ $business->id }},
                        rating: this.rating,
                        comment: this.comment
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        alert('¡Reseña enviada con éxito!');
                        this.showModal = false;
                        this.rating = 0;
                        this.comment = '';
                        window.location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error al enviar la reseña');
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }" x-init="init()" x-show="showModal" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
        <div @click.away="showModal = false" x-show="showModal" x-transition.scale
            class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900">Dejar una reseña</h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Calificación</label>
                <div class="flex gap-2">
                    <template x-for="i in 5">
                        <button @click="rating = i" class="text-3xl transition-colors"
                            :class="rating >= i ? 'text-yellow-500' : 'text-gray-300 hover:text-yellow-400'">
                            ★
                        </button>
                    </template>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Comentario (opcional)</label>
                <textarea x-model="comment" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
                    placeholder="Cuéntanos tu experiencia..."></textarea>
            </div>

            <div class="flex gap-3">
                <button @click="showModal = false"
                    class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    Cancelar
                </button>
                <button @click="submitReview()" :disabled="loading"
                    class="flex-1 px-4 py-2 text-white bg-green-600 rounded-lg hover:bg-green-700 transition-colors font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                    <span x-show="!loading">Enviar</span>
                    <span x-show="loading">Enviando...</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal para ver todas las reseñas --}}
    <div x-data="{
        showModal: false,
        init() {
            window.addEventListener('open-reviews-modal', () => {
                this.showModal = true;
            });
        }
    }" x-init="init()" x-show="showModal" x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" style="display: none;">
        <div @click.away="showModal = false" x-show="showModal" x-transition.scale
            class="bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[80vh] flex flex-col">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">Todas las Reseñas</h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-6">
                @if ($business->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach ($business->reviews as $review)
                            <div class="bg-gray-50 rounded-xl p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                            <span class="text-sm font-bold text-emerald-600">
                                                {{ strtoupper(substr($review->user->name ?? 'A', 0, 1)) }}
                                            </span>
                                        </div>
                                        <span
                                            class="font-medium text-gray-900">{{ $review->user->name ?? 'Anónimo' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span
                                                class="{{ $i <= $review->rating ? 'text-yellow-500' : 'text-gray-300' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                                @if ($review->comment)
                                    <p class="text-sm text-gray-600 mt-2">{{ $review->comment }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                        </svg>
                        <p class="text-gray-500">Aún no hay reseñas</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>
