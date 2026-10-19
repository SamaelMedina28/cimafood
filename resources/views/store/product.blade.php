<x-client-layout>
    @php
        $business = $product->business;
        $soldOut = $product->quantity <= 0;
        $payload = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => (float) $product->price,
            'image' => $product->image_url ?? '',
            'businessId' => $business->id,
            'businessName' => $business->name,
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-16">
        <a href="{{ route('store.business', $business) }}" wire:navigate
            class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-900">
            <x-icon name="chevron-left" class="size-4" />
            {{ $business->name }}
        </a>

        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-14">
            <div class="aspect-square overflow-hidden rounded-3xl bg-gray-100">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                        @class(['size-full object-cover', 'grayscale opacity-60' => $soldOut])>
                @else
                    <div class="flex size-full items-center justify-center text-gray-300"><x-icon name="photo" class="size-12" /></div>
                @endif
            </div>

            <div class="flex flex-col" x-data="{
                isFavorite: {{ $isFavorite ? 'true' : 'false' }},
                loading: false,
                toggleFavorite() {
                    if (this.loading) return;
                    this.loading = true;
                    fetch('{{ route('store.favorites.toggle', $product->id) }}', {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        })
                        .then(res => res.json())
                        .then(data => {
                            this.isFavorite = data.isFavorite;
                            this.$dispatch('toast-show', { message: data.isFavorite ? 'Guardado en favoritos' : 'Quitado de favoritos' });
                        })
                        .finally(() => this.loading = false);
                }
            }">
                <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-tight text-gray-900 leading-tight">{{ $product->name }}</h1>
                <p class="mt-3 font-display text-3xl font-bold text-gray-900 tabular-nums">${{ number_format($product->price, 2) }}</p>
                <p class="mt-5 text-lg text-gray-600">{{ $product->description }}</p>

                <p class="mt-5 text-sm font-medium">
                    @if ($soldOut)
                        <span class="text-gray-500">Agotado por ahora</span>
                    @elseif ($product->quantity <= 5)
                        <span class="text-amber-700">Quedan {{ $product->quantity }}</span>
                    @else
                        <span class="text-green-700">Disponible</span>
                    @endif
                </p>

                <div class="mt-8 flex gap-3">
                    <button type="button" @click="$dispatch('add-to-cart', {{ Js::from($payload) }})" @disabled($soldOut)
                        class="flex-1 inline-flex items-center justify-center gap-2 rounded-full bg-green-600 px-6 py-3.5 font-semibold text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2 transition-colors disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                        <x-icon name="plus" class="size-5" />
                        Agregar al carrito
                    </button>
                    <button type="button" @click="toggleFavorite()" :disabled="loading"
                        :aria-label="isFavorite ? 'Quitar de favoritos' : 'Guardar en favoritos'"
                        class="inline-flex size-[52px] items-center justify-center rounded-full border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-colors {{ $isFavorite ? 'text-rose-500' : 'text-gray-700' }}"
                        :class="{ 'text-rose-500': isFavorite, 'text-gray-700': !isFavorite }">
                        <svg class="size-6" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" stroke-linecap="round"
                            stroke-linejoin="round" fill="{{ $isFavorite ? 'currentColor' : 'none' }}" :fill="isFavorite ? 'currentColor' : 'none'">
                            <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                        </svg>
                    </button>
                </div>

                <a href="{{ route('store.business', $business) }}" wire:navigate
                    class="mt-8 flex items-center gap-3 rounded-2xl border border-gray-200 p-4 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                    <span class="size-11 shrink-0 overflow-hidden rounded-full bg-green-50">
                        @if ($business->logo_url)
                            <img src="{{ $business->logo_url }}" alt="" class="size-full object-cover">
                        @endif
                    </span>
                    <span class="min-w-0 flex-1">
                        <span class="block font-semibold text-gray-900 truncate">{{ $business->name }}</span>
                        <span class="block text-sm {{ $business->isOpen() ? 'text-green-700' : 'text-gray-500' }}">
                            {{ $business->isOpen() ? 'Abierto, cierra a las ' . substr($business->close_time, 0, 5) : 'Cerrado, abre a las ' . substr($business->open_time, 0, 5) }}
                        </span>
                    </span>
                    <x-icon name="chevron-right" class="size-5 text-gray-400" />
                </a>
            </div>
        </div>

        @if ($related->count() > 0)
            <section class="mt-16" aria-labelledby="related-title">
                <h2 id="related-title" class="font-display text-2xl font-bold text-gray-900">Más de {{ $business->name }}</h2>
                <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-8 sm:gap-x-6">
                    @foreach ($related as $item)
                        <x-store.product-card :product="$item" :business="$business" />
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</x-client-layout>
