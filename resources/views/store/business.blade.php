<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <a href="{{ route('store') }}" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <h1 class="text-lg font-bold text-gray-900 truncate px-4">{{ $business->name }}</h1>
            <div x-data="{ showCartInfo: false }" @cart-updated.window="showCartInfo = true; setTimeout(() => showCartInfo = false, 2000)" class="relative">
                <button @click="$dispatch('open-cart')" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200 relative">
                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <!-- Cart Badge -->
                    <span id="cart-badge" class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full" style="display: none;">
                        0
                    </span>
                </button>
                <div x-show="showCartInfo" x-transition class="absolute right-0 mt-2 w-48 bg-emerald-600 text-white text-sm rounded-lg py-2 px-3 shadow-lg z-50 text-center">
                    Producto agregado
                </div>
            </div>
        </div>
    </x-slot>

    <div x-data="cartManager({{ $business->id }})" x-init="initCart()" class="pb-24 bg-gray-50 min-h-screen">
        {{-- Banner --}}
        <div class="relative w-full h-48 sm:h-64 bg-gray-300">
            @if($business->banner)
                <img src="{{ str_starts_with($business->banner, 'http') ? $business->banner : asset('storage/' . $business->banner) }}" alt="Banner {{ $business->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-r from-emerald-500 to-teal-500">
                    <span class="text-white/50 text-4xl font-bold">Sin Portada</span>
                </div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        </div>

        {{-- Info del Negocio --}}
        <div class="px-4 relative z-10 -mt-16 sm:-mt-20 max-w-5xl mx-auto">
            <div class="bg-white rounded-2xl shadow-md p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-end gap-4">
                {{-- Logo --}}
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white flex-shrink-0">
                    @if($business->logo)
                        <img src="{{ str_starts_with($business->logo, 'http') ? $business->logo : asset('storage/' . $business->logo) }}" alt="{{ $business->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-emerald-100 flex items-center justify-center">
                            <span class="text-4xl font-bold text-emerald-600">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                <div class="flex-grow text-center sm:text-left mt-2 sm:mt-0">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900">{{ $business->name }}</h1>
                    <p class="text-sm text-gray-500 mt-1 line-clamp-2">{{ $business->description ?? 'Sin descripción disponible.' }}</p>
                    <div class="flex items-center justify-center sm:justify-start gap-4 mt-3">
                        {{--  Horario de Atención --}}
                        @php
                        // Sacar la hora actual
                            $horaActual = date('H:i');
                            $isOpen = $horaActual >= $business->open_time && $horaActual <= $business->close_time;
                        @endphp
                        <div class="text-sm font-medium text-gray-700 border px-3 py-1 rounded-full {{ $isOpen ? 'bg-green-100 border-green-400' : 'bg-red-100 border-red-400' }}">
                            {{ $business->open_time }} - {{ $business->close_time }}
                        </div>
                        <div class="flex items-center text-sm font-medium text-gray-700 bg-gray-100 px-3 py-1 rounded-full">
                            <span class="text-yellow-500 mr-1">★</span> {{ number_format($business->averageRating() ?? 0, 1) }} ({{ $business->totalReviews() }}+)
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Menú de Productos --}}
        <div class="mt-8 px-4 max-w-5xl mx-auto">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">🍽️</span> Nuestro Menú
            </h2>

            @if($business->products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($business->products as $product)
                        <div class="bg-white rounded-2xl p-3 flex gap-4 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                            {{-- Imagen del producto --}}
                            <div class="w-24 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                                @if($product->image_path)
                                    <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-3xl">🍲</div>
                                @endif
                            </div>

                            {{-- Info del producto --}}
                            <div class="flex flex-col justify-between flex-grow">
                                <div>
                                    <h3 class="text-base font-bold text-gray-900 leading-tight">{{ $product->name }}</h3>
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $product->description }}</p>
                                </div>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-lg font-extrabold text-green-600">${{ number_format($product->price, 2) }}</span>
                                    <button @click="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, '{{ $product->image_path ? (str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path)) : '' }}')" class="w-8 h-8 rounded-full bg-green-500 hover:bg-green-600 flex items-center justify-center shadow-sm transition-colors transform active:scale-95">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed">
                    <div class="text-5xl mb-3">👨‍🍳</div>
                    <h3 class="text-lg font-bold text-gray-700">Aún no hay productos</h3>
                    <p class="text-sm text-gray-500 mt-1">Este negocio pronto agregará platillos deliciosos.</p>
                </div>
            @endif
        </div>

        {{-- Floating Cart Bottom Bar (Optional, for easy access) --}}
        <div x-show="totalItems > 0"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-full opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-full opacity-0"
             class="fixed bottom-0 left-0 right-0 p-4 z-40 lg:hidden pointer-events-none">
            <button @click="$dispatch('open-cart')" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl p-4 shadow-xl flex items-center justify-between pointer-events-auto transform transition-transform active:scale-[0.98]">
                <div class="flex items-center gap-3">
                    <div class="bg-emerald-800 rounded-full w-8 h-8 flex items-center justify-center font-bold text-sm" x-text="totalItems"></div>
                    <span class="font-semibold text-sm">Ver pedido</span>
                </div>
                <span class="font-extrabold text-lg" x-text="'$' + totalPrice.toFixed(2)"></span>
            </button>
        </div>

        {{-- Cart Drawer --}}
        <div x-data="{ open: false }" @open-cart.window="open = true" @close-cart.window="open = false" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true" x-show="open">
            <div x-show="open" x-transition:enter="ease-in-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in-out duration-500" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <div x-show="open" @click.away="open = false" x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" class="pointer-events-auto w-screen max-w-md">
                            <div class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl">
                                <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                                    <div class="flex items-start justify-between">
                                        <h2 class="text-lg font-medium text-gray-900" id="slide-over-title">Tu Carrito</h2>
                                        <div class="ml-3 flex h-7 items-center">
                                            <button type="button" @click="open = false" class="relative -m-2 p-2 text-gray-400 hover:text-gray-500">
                                                <span class="absolute -inset-0.5"></span>
                                                <span class="sr-only">Cerrar panel</span>
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mt-8">
                                        <div class="flow-root">
                                            <template x-if="cart.length === 0">
                                                <div class="text-center py-10">
                                                    <div class="text-6xl mb-4">🛒</div>
                                                    <p class="text-gray-500">Tu carrito está vacío</p>
                                                    <button @click="open = false" class="mt-4 text-emerald-600 font-semibold hover:text-emerald-700">Continuar explorando</button>
                                                </div>
                                            </template>
                                            <ul role="list" class="-my-6 divide-y divide-gray-200">
                                                <template x-for="(item, index) in cart" :key="item.id">
                                                    <li class="flex py-6">
                                                        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 bg-gray-50 flex items-center justify-center">
                                                            <template x-if="item.image">
                                                                <img :src="item.image" :alt="item.name" class="h-full w-full object-cover object-center">
                                                            </template>
                                                            <template x-if="!item.image">
                                                                <span class="text-2xl">🍲</span>
                                                            </template>
                                                        </div>
                                                        <div class="ml-4 flex flex-1 flex-col">
                                                            <div>
                                                                <div class="flex justify-between text-sm font-medium text-gray-900">
                                                                    <h3 x-text="item.name"></h3>
                                                                    <p class="ml-4 font-bold text-emerald-600" x-text="'$' + (item.price * item.quantity).toFixed(2)"></p>
                                                                </div>
                                                            </div>
                                                            <div class="flex flex-1 items-end justify-between text-sm">
                                                                <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                                                    <button @click="updateQuantity(index, item.quantity - 1)" class="px-2 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors">-</button>
                                                                    <span class="px-3 py-1 font-semibold text-gray-900" x-text="item.quantity"></span>
                                                                    <button @click="updateQuantity(index, item.quantity + 1)" class="px-2 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors">+</button>
                                                                </div>
                                                                <div class="flex">
                                                                    <button type="button" @click="removeFromCart(index)" class="font-medium text-red-500 hover:text-red-400">Eliminar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <template x-if="cart.length > 0">
                                    <div class="border-t border-gray-200 px-4 py-6 sm:px-6">
                                        <div class="flex justify-between text-base font-medium text-gray-900 mb-4">
                                            <p>Subtotal</p>
                                            <p x-text="'$' + totalPrice.toFixed(2)"></p>
                                        </div>
                                        <p class="mt-0.5 text-sm text-gray-500">Impuestos y envío calculados al procesar el pago.</p>
                                        <div class="mt-6">
                                            <button @click="checkout()" :disabled="isCheckingOut" class="w-full flex items-center justify-center rounded-xl border border-transparent bg-emerald-600 px-6 py-3.5 text-base font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                                <span x-show="!isCheckingOut">Realizar pedido</span>
                                                <span x-show="isCheckingOut">Procesando...</span>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.cartManager = function(businessId) {
            return {
                cart: [],
                businessId: businessId,
                storageKey: 'cimafood_cart',
                isCheckingOut: false,

                get totalItems() {
                    return this.cart.reduce((sum, item) => sum + item.quantity, 0);
                },

                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                initCart() {
                    let storedCart = localStorage.getItem(this.storageKey);
                    if (storedCart) {
                        try {
                            let parsed = JSON.parse(storedCart);
                            // Solo se carga el carrito si es del mismo negocio, de lo contrario se borra o pregunta
                            if (parsed.businessId === this.businessId) {
                                this.cart = parsed.items;
                            } else {
                                // Borramos el carrito si es de otro negocio
                                this.cart = [];
                                localStorage.removeItem(this.storageKey);
                            }
                        } catch (e) {
                            this.cart = [];
                        }
                    }
                    this.updateBadge();
                },

                saveCart() {
                    localStorage.setItem(this.storageKey, JSON.stringify({
                        businessId: this.businessId,
                        items: this.cart
                    }));
                    this.updateBadge();
                },

                updateBadge() {
                    const badge = document.getElementById('cart-badge');
                    if (badge) {
                        badge.innerText = this.totalItems;
                        badge.style.display = this.totalItems > 0 ? 'inline-flex' : 'none';
                    }
                },

                addToCart(id, name, price, image) {
                    const existingItemIndex = this.cart.findIndex(item => item.id === id);
                    if (existingItemIndex > -1) {
                        this.cart[existingItemIndex].quantity += 1;
                    } else {
                        this.cart.push({
                            id: id,
                            name: name,
                            price: parseFloat(price),
                            image: image,
                            quantity: 1
                        });
                    }
                    this.saveCart();
                    this.$dispatch('cart-updated');
                },

                updateQuantity(index, quantity) {
                    if (quantity <= 0) {
                        this.removeFromCart(index);
                        return;
                    }
                    this.cart[index].quantity = quantity;
                    this.saveCart();
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                    // Close cart if empty
                    if (this.cart.length === 0) {
                        // We could automatically close it here
                    }
                },

                async checkout() {
                    if (this.cart.length === 0 || this.isCheckingOut) return;
                    this.isCheckingOut = true;

                    try {
                        const response = await fetch('{{ route('store.checkout') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                business_id: this.businessId,
                                items: this.cart.map(item => ({ id: item.id, quantity: item.quantity }))
                            })
                        });

                        if (response.ok) {
                            alert('¡Pedido realizado con éxito!');
                            this.cart = [];
                            this.saveCart();
                            this.$dispatch('close-cart');
                        } else {
                            const errorData = await response.json();
                            alert(errorData.error || 'Hubo un error al procesar el pedido. Intenta nuevamente.');
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Error de conexión. Intenta nuevamente.');
                    } finally {
                        this.isCheckingOut = false;
                    }
                }
            };
        };
    </script>
</x-client-layout>
