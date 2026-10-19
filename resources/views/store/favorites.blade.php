<x-client-layout>
    <x-slot name="header">
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Favoritos</h1>
        <p class="mt-1 text-gray-500">Los platillos que guardaste para pedir otra vez.</p>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        @if ($favorites->count() > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6">
                @foreach ($favorites as $product)
                    @if ($product->business)
                        <x-store.product-card :product="$product" :favorite="true" remove-on-unfavorite />
                    @endif
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-gray-50 px-6 py-16 text-center">
                <x-icon name="heart" class="mx-auto size-10 text-gray-300" />
                <h2 class="mt-4 font-display text-lg font-bold text-gray-900">Aún no tienes favoritos</h2>
                <p class="mx-auto mt-1 max-w-sm text-sm text-gray-500">Toca el corazón en cualquier platillo del menú
                    de un negocio para guardarlo aquí.</p>
                <a href="{{ route('store.businesses') }}" wire:navigate
                    class="mt-6 inline-flex rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                    Ver negocios</a>
            </div>
        @endif
    </div>
</x-client-layout>
