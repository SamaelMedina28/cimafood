@props([
    'product',
    'business' => null,
    // null = sin botón de favorito; true/false = estado inicial
    'favorite' => null,
    // En la página de favoritos la tarjeta desaparece al quitarla
    'removeOnUnfavorite' => false,
    // 'card' para grids y carruseles, 'row' para el menú de un negocio
    'variant' => 'card',
])

@php
    $business = $business ?? $product->business;
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

<article x-data="{
    isFavorite: {{ $favorite ? 'true' : 'false' }},
    removed: false,
    loading: false,
    toggleFavorite() {
        if (this.loading) return;
        this.loading = true;
        fetch('{{ route('store.favorites.toggle', $product->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                this.isFavorite = data.isFavorite;
                @if ($removeOnUnfavorite) this.removed = !data.isFavorite; @endif
                this.$dispatch('toast-show', { message: data.isFavorite ? 'Guardado en favoritos' : 'Quitado de favoritos' });
            })
            .catch(err => console.error(err))
            .finally(() => this.loading = false);
    }
}" x-show="!removed" x-transition.opacity
    {{ $attributes->class([
        'group relative bg-white',
        'flex flex-col' => $variant === 'card',
        'flex gap-4 py-4' => $variant === 'row',
    ]) }}>

    {{-- Imagen --}}
    <div @class([
        'relative overflow-hidden bg-gray-100 shrink-0',
        'aspect-[4/3] w-full rounded-2xl' => $variant === 'card',
        'size-24 sm:size-28 rounded-xl order-last' => $variant === 'row',
    ])>
        @if ($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"
                @class(['size-full object-cover', 'grayscale opacity-60' => $soldOut])>
        @else
            <div class="size-full flex items-center justify-center text-gray-300">
                <x-icon name="photo" class="size-8" />
            </div>
        @endif

        @if ($favorite !== null)
            <button type="button" @click="toggleFavorite()" :disabled="loading"
                :aria-label="isFavorite ? 'Quitar de favoritos' : 'Guardar en favoritos'"
                class="absolute top-2 right-2 z-10 size-8 rounded-full bg-white/90 backdrop-blur flex items-center justify-center shadow-sm transition-colors hover:text-gray-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 {{ $favorite ? 'text-rose-500' : 'text-gray-500' }}"
                :class="{ 'text-rose-500 hover:text-rose-600': isFavorite, 'text-gray-500': !isFavorite }">
                <svg class="size-[18px]" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"
                    stroke-linecap="round" stroke-linejoin="round" fill="{{ $favorite ? 'currentColor' : 'none' }}" :fill="isFavorite ? 'currentColor' : 'none'">
                    <path
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
            </button>
        @endif

        @if ($soldOut && $variant === 'card')
            <span class="absolute bottom-2 left-2 rounded-full bg-gray-900/80 px-2.5 py-1 text-xs font-medium text-white">
                Agotado
            </span>
        @endif
    </div>

    {{-- Info --}}
    <div @class(['flex flex-1 flex-col min-w-0', 'pt-3' => $variant === 'card'])>
        <h3 class="font-semibold text-gray-900 leading-snug line-clamp-1">
            <a href="{{ route('store.product', $product) }}" wire:navigate class="hover:text-green-700 focus:outline-none focus-visible:underline">
                {{ $product->name }}
                {{-- Toda la foto lleva a la página del producto --}}
                <span class="absolute inset-x-0 top-0 {{ $variant === 'card' ? 'aspect-[4/3]' : 'hidden' }}" aria-hidden="true"></span>
            </a>
        </h3>

        @if ($variant === 'card' && !request()->routeIs('store.business'))
            <a href="{{ route('store.business', $business->id) }}" wire:navigate
                class="mt-0.5 text-sm text-gray-500 hover:text-green-700 truncate">{{ $business->name }}</a>
        @endif

        <p class="mt-1 text-sm text-gray-500 line-clamp-2">{{ $product->description }}</p>

        <div class="mt-auto pt-3 flex items-center justify-between gap-3">
            <div>
                <p class="font-display text-lg font-bold text-gray-900 tabular-nums">
                    ${{ number_format($product->price, 2) }}</p>
                @if ($soldOut)
                    <p class="text-xs font-medium text-gray-500">Agotado</p>
                @elseif ($product->quantity <= 5)
                    <p class="text-xs font-medium text-amber-700">Quedan {{ $product->quantity }}</p>
                @endif
            </div>

            <button type="button" @click="$dispatch('add-to-cart', {{ Js::from($payload) }})"
                @disabled($soldOut)
                aria-label="Agregar {{ $product->name }} al carrito"
                class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-green-600 p-2 sm:pl-3 sm:pr-3.5 text-sm font-semibold text-white transition-colors hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2 disabled:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                <x-icon name="plus" class="size-5 sm:size-4" />
                <span class="hidden sm:inline">Agregar</span>
            </button>
        </div>
    </div>
</article>
