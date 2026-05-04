<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full p-2">
            <h1 class="text-lg font-bold text-gray-900 truncate">Mis Favoritos</h1>
        </div>
    </x-slot>

    <div class="pb-24 min-h-screen px-4 py-6 max-w-5xl mx-auto">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="text-2xl text-yellow-500"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg></span> Platos Favoritos
        </h2>

        @if($favorites->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($favorites as $product)
                    <div x-data="{ isFavorite: true }" x-show="isFavorite" x-transition class="bg-white rounded-2xl p-4 flex flex-col gap-3 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex gap-4">
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">🍲</div>
                                @endif
                            </div>
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                </div>
                                <div class="mt-2 text-sm font-medium text-emerald-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    {{ $product->business->name ?? 'Tienda Desconocida' }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-1 pt-3 border-t border-gray-50">
                            <span class="text-lg font-extrabold text-green-600">${{ number_format($product->price, 2) }}</span>
                            <div class="flex items-center gap-2">
                                <button
                                    x-data="{
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
                                                isFavorite = data.isFavorite;
                                            })
                                            .catch(err => console.error(err))
                                            .finally(() => {
                                                this.loading = false;
                                            });
                                        }
                                    }"
                                    @click="toggle()"
                                    class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm transition-colors transform active:scale-95"
                                    :class="isFavorite ? 'bg-yellow-100 hover:bg-yellow-200 text-yellow-600' : 'bg-gray-100 hover:bg-gray-200 text-gray-400'"
                                    :disabled="loading"
                                >
                                    <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'scale-110': isFavorite }" :fill="isFavorite ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                                @if($product->business)
                                    <a href="{{ route('store.business', $product->business->id) }}" wire:navigate class="px-4 py-1.5 rounded-full bg-green-600 hover:bg-green-700 text-white font-semibold text-sm shadow-sm transition-colors transform active:scale-95 flex items-center gap-1">
                                        Ver tienda
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed mt-8 max-w-md mx-auto">
                <div class="mx-auto flex justify-center mb-4 text-gray-300">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800">Aún no tienes favoritos</h3>
                <p class="text-md text-gray-500 mt-2">Explora las tiendas y presiona el corazón en los platillos que más te gusten para guardarlos aquí.</p>
                <a href="{{ route('store') }}" wire:navigate class="inline-flex items-center mt-4 px-6 py-2 bg-green-700 border border-green-700 rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:green-400 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Explorar comida
                    </div>
                </a>
            </div>
        @endif
    </div>
</x-client-layout>
