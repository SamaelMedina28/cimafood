<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('store') }}" wire:navigate
                class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900">Todos los Productos</h1>
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
                <span x-show="count > 0" x-text="count" x-transition
                    class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-500 rounded-full"></span>
            </button>
        </div>
    </x-slot>

    <div class="pb-24 bg-gray-50 min-h-screen">
        <div class="px-4 py-6 max-w-5xl mx-auto">
            @if ($products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($products as $product)
                        <div
                            class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            {{-- Imagen --}}
                            <div class="relative w-full h-40 bg-gray-100 overflow-hidden">
                                @if ($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                        alt="{{ $product->name }}"
                                        class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-emerald-50">🍽️</div>
                                @endif
                            </div>

                            {{-- Info --}}
                            <div class="p-4">
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
                                
                                <div class="flex items-center justify-between mt-3">
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($product->price, 2) }}</span>
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
                                        class="w-8 h-8 rounded-full bg-green-600 hover:bg-green-700 flex items-center justify-center transition-colors duration-200 shadow-sm {{ isset($product->quantity) && $product->quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
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
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if ($products->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @endif
            @else
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    <h3 class="text-lg font-bold text-gray-700">No hay productos disponibles</h3>
                    <p class="text-sm text-gray-500 mt-1">Pronto habrá nuevos productos para ti.</p>
                </div>
            @endif
        </div>
    </div>
</x-client-layout>
