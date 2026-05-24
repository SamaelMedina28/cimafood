<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('store') }}" wire:navigate
                class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Todos los Negocios</h1>
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
                <div class="text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <span x-show="count > 0" x-text="count" x-transition
                    class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
            </button>
        </div>
    </x-slot>

    <div class="pb-24 bg-gray-50 min-h-screen">
        <div class="px-4 py-6 max-w-5xl mx-auto">
            @if ($businesses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($businesses as $business)
                        <a href="{{ route('store.business', $business->id) }}" wire:navigate
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            {{-- Banner --}}
                            <div class="relative w-full h-32 bg-gray-200">
                                @if ($business->banner)
                                    <img src="{{ str_starts_with($business->banner, 'http') ? $business->banner : asset('storage/' . $business->banner) }}"
                                        alt="{{ $business->name }}" class="w-full h-full object-cover">
                                @else
                                    <div
                                        class="w-full h-full flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500">
                                        <span class="text-white/50 text-3xl font-bold">Sin Portada</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-md bg-white flex-shrink-0">
                                        @if ($business->logo)
                                            <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}"
                                                alt="{{ $business->name }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                                                <span
                                                    class="text-lg font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <h3 class="text-base font-bold text-gray-900 truncate">{{ $business->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 mt-1 line-clamp-2">
                                            {{ $business->description ?? 'Sin descripción' }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between mt-3">
                                    <div class="flex items-center gap-2">
                                        <span class="text-yellow-500 text-sm">★</span>
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ number_format($business->averageRating() ?? 0, 1) }}</span>
                                        <span class="text-xs text-gray-400">({{ $business->totalReviews() }})</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $business->products->count() }}
                                        productos</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if ($businesses->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $businesses->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-700">No hay negocios disponibles</h3>
                    <p class="text-sm text-gray-500 mt-1">Pronto habrá nuevos negocios para ti.</p>
                </div>
            @endif
        </div>
    </div>
</x-client-layout>
