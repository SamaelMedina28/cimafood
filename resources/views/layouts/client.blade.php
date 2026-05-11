<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        {{-- Todo el body vive dentro del scope del carrito global --}}
        <div class="min-h-screen bg-gray-100" x-data="globalCart()" x-init="initCart()" @add-to-cart.window="addToCart($event.detail)">

            {{-- Nav de Livewire --}}
            @livewire('client-navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                  <header class="bg-white shadow">
                      <div class="max-w-7xl mx-auto py-2 px-4 sm:px-6 lg:px-8">
                          {{ $header }}
                      </div>
                  </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            {{-- ============================================================ --}}
            {{-- CART DRAWER GLOBAL (agrupado por negocio)                    --}}
            {{-- ============================================================ --}}
            <div
                x-data="{ open: false }"
                @open-cart.window="open = true"
                @close-cart.window="open = false"
                class="relative z-50"
                role="dialog"
                aria-modal="true"
                x-show="open"
                style="display: none;"
            >
                {{-- Overlay --}}
                <div
                    x-show="open"
                    x-transition:enter="ease-in-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in-out duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500/75 transition-opacity"
                    @click="open = false"
                ></div>

                <div class="fixed inset-0 overflow-hidden pointer-events-none">
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                            <div
                                x-show="open"
                                x-transition:enter="transform transition ease-in-out duration-500"
                                x-transition:enter-start="translate-x-full"
                                x-transition:enter-end="translate-x-0"
                                x-transition:leave="transform transition ease-in-out duration-500"
                                x-transition:leave-start="translate-x-0"
                                x-transition:leave-end="translate-x-full"
                                class="pointer-events-auto w-screen max-w-md"
                            >
                                <div class="flex h-full flex-col bg-white shadow-xl">

                                    {{-- Header --}}
                                    <div class="flex items-center justify-between px-4 py-6 sm:px-6 bg-gray-50 border-b border-gray-200">
                                        <h2 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                            🛒 Tu Carrito
                                            <span x-show="totalItems > 0" class="text-sm font-normal text-gray-500" x-text="'(' + totalItems + ' producto' + (totalItems > 1 ? 's' : '') + ')'"></span>
                                        </h2>
                                        <button type="button" @click="open = false" class="p-2 text-gray-400 hover:text-gray-600 transition-colors rounded-full hover:bg-gray-100">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Contenido --}}
                                    <div class="flex-1 overflow-y-auto">

                                        {{-- Carrito vacío --}}
                                        <template x-if="cart.length === 0">
                                            <div class="flex flex-col items-center justify-center h-full py-20 px-6 text-center">
                                                <div class="text-7xl mb-4">🛒</div>
                                                <p class="text-lg font-semibold text-gray-700">Tu carrito está vacío</p>
                                                <p class="text-sm text-gray-500 mt-1">Agrega productos de cualquier negocio</p>
                                                <button @click="open = false" class="mt-6 text-emerald-600 font-semibold hover:text-emerald-700 text-sm">
                                                    Explorar tiendas →
                                                </button>
                                            </div>
                                        </template>

                                        {{-- Items agrupados por negocio --}}
                                        <template x-if="cart.length > 0">
                                            <div class="px-4 py-4 sm:px-6 space-y-6">
                                                <template x-for="group in groupedCart" :key="group.businessId">
                                                    <div class="border border-gray-200 rounded-2xl overflow-hidden">
                                                        {{-- Cabecera del negocio --}}
                                                        <div class="bg-emerald-50 px-4 py-3 flex items-center gap-2 border-b border-emerald-100">
                                                            <span class="text-emerald-600 text-lg">🏪</span>
                                                            <span class="font-bold text-emerald-800 text-sm" x-text="group.businessName"></span>
                                                        </div>
                                                        {{-- Productos del negocio --}}
                                                        <ul class="divide-y divide-gray-100 bg-white">
                                                            <template x-for="(item, idx) in group.items" :key="item.id">
                                                                <li class="flex py-4 px-4 gap-3">
                                                                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center">
                                                                        <template x-if="item.image">
                                                                            <img :src="item.image" :alt="item.name" class="h-full w-full object-cover">
                                                                        </template>
                                                                        <template x-if="!item.image">
                                                                            <span class="text-2xl">🍲</span>
                                                                        </template>
                                                                    </div>
                                                                    <div class="flex flex-1 flex-col gap-2">
                                                                        <div class="flex justify-between text-sm font-medium text-gray-900">
                                                                            <span x-text="item.name" class="leading-tight"></span>
                                                                            <span class="ml-4 font-bold text-emerald-600 whitespace-nowrap" x-text="'$' + (item.price * item.quantity).toFixed(2)"></span>
                                                                        </div>
                                                                        <div class="flex items-center justify-between">
                                                                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden text-sm">
                                                                                <button @click="updateQuantityByIndex(item.cartIndex, item.quantity - 1)" class="px-2.5 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold transition-colors">−</button>
                                                                                <span class="px-3 py-1 font-semibold text-gray-900" x-text="item.quantity"></span>
                                                                                <button @click="updateQuantityByIndex(item.cartIndex, item.quantity + 1)" class="px-2.5 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold transition-colors">+</button>
                                                                            </div>
                                                                            <button type="button" @click="removeFromCartByIndex(item.cartIndex)" class="text-xs font-medium text-red-500 hover:text-red-700 transition-colors">
                                                                                Eliminar
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Footer con total y checkout --}}
                                    <template x-if="cart.length > 0">
                                        <div class="border-t border-gray-200 px-4 py-5 sm:px-6 bg-white">
                                            <div class="space-y-2 mb-4">
                                                <div class="flex justify-between text-sm text-gray-600">
                                                    <span x-text="'Negocios (' + groupedCart.length + ')'"></span>
                                                    <span class="text-xs text-gray-400">Se crearán pedidos separados</span>
                                                </div>
                                                <div class="flex justify-between text-base font-bold text-gray-900">
                                                    <span>Total</span>
                                                    <span x-text="'$' + totalPrice.toFixed(2)" class="text-emerald-600"></span>
                                                </div>
                                            </div>
                                            <button
                                                @click="checkout()"
                                                :disabled="isCheckingOut"
                                                class="w-full flex items-center justify-center rounded-xl bg-emerald-600 px-6 py-3.5 text-base font-semibold text-white shadow-md hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                            >
                                                <span x-show="!isCheckingOut">Realizar pedido</span>
                                                <span x-show="isCheckingOut" class="flex items-center gap-2">
                                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 14.627 0 12 8z"></path>
                                                    </svg>
                                                    Procesando...
                                                </span>
                                            </button>
                                        </div>
                                    </template>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- /globalCart scope --}}

        @stack('modals')

        @livewireScripts

        <script>
            window.globalCart = function() {
                return {
                    cart: [],
                    storageKey: 'cimafood_global_cart',
                    isCheckingOut: false,
                    checkoutUrl: '{{ route('store.checkout') }}',
                    csrfToken: '{{ csrf_token() }}',

                    get totalItems() {
                        return this.cart.reduce((sum, item) => sum + item.quantity, 0);
                    },

                    get totalPrice() {
                        return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                    },

                    /**
                     * Agrupa los ítems del carrito por negocio.
                     * Cada grupo contiene: businessId, businessName, items[]
                     * Cada item incluye su índice original (cartIndex) para poder actualizarlo.
                     */
                    get groupedCart() {
                        const groups = {};
                        this.cart.forEach((item, index) => {
                            const key = item.businessId;
                            if (!groups[key]) {
                                groups[key] = {
                                    businessId: item.businessId,
                                    businessName: item.businessName,
                                    items: []
                                };
                            }
                            groups[key].items.push({ ...item, cartIndex: index });
                        });
                        return Object.values(groups);
                    },

                    initCart() {
                        try {
                            const stored = localStorage.getItem(this.storageKey);
                            if (stored) {
                                this.cart = JSON.parse(stored);
                            }
                        } catch (e) {
                            this.cart = [];
                        }
                        this.$nextTick(() => {
                            window.dispatchEvent(new CustomEvent('cart-count-updated', { detail: { count: this.totalItems } }));
                        });
                    },

                    saveCart() {
                        localStorage.setItem(this.storageKey, JSON.stringify(this.cart));
                        window.dispatchEvent(new CustomEvent('cart-count-updated', { detail: { count: this.totalItems } }));
                    },

                    /**
                     * Agrega un producto al carrito.
                     * El detalle del evento debe incluir: { id, name, price, image, businessId, businessName }
                     */
                    addToCart(detail) {
                        const existingIndex = this.cart.findIndex(
                            item => item.id === detail.id && item.businessId === detail.businessId
                        );
                        if (existingIndex > -1) {
                            this.cart[existingIndex].quantity += 1;
                        } else {
                            this.cart.push({
                                id: detail.id,
                                name: detail.name,
                                price: parseFloat(detail.price),
                                image: detail.image || '',
                                businessId: detail.businessId,
                                businessName: detail.businessName,
                                quantity: 1
                            });
                        }
                        this.saveCart();
                        this.$dispatch('cart-updated');
                    },

                    updateQuantityByIndex(index, quantity) {
                        if (quantity <= 0) {
                            this.removeFromCartByIndex(index);
                            return;
                        }
                        this.cart[index].quantity = quantity;
                        this.saveCart();
                    },

                    removeFromCartByIndex(index) {
                        this.cart.splice(index, 1);
                        this.saveCart();
                    },

                    /**
                     * Checkout: crea un pedido por cada negocio en el carrito.
                     * Hace las solicitudes en paralelo con Promise.all.
                     */
                    async checkout() {
                        if (this.cart.length === 0 || this.isCheckingOut) return;
                        this.isCheckingOut = true;

                        const groups = this.groupedCart;

                        try {
                            const requests = groups.map(group => {
                                return fetch(this.checkoutUrl, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': this.csrfToken,
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        business_id: group.businessId,
                                        items: group.items.map(item => ({
                                            id: item.id,
                                            quantity: item.quantity
                                        }))
                                    })
                                });
                            });

                            const responses = await Promise.all(requests);

                            const allOk = responses.every(r => r.ok);

                            if (allOk) {
                                const count = groups.length;
                                alert(`¡Pedido realizado con éxito! Se crearon ${count} pedido${count > 1 ? 's' : ''} (uno por negocio).`);
                                this.cart = [];
                                this.saveCart();
                                this.$dispatch('close-cart');
                                // Redirigir a pedidos
                                window.location.href = '{{ route('store.orders') }}';
                            } else {
                                // Buscar el primer error
                                for (const response of responses) {
                                    if (!response.ok) {
                                        const errorData = await response.json();
                                        alert(errorData.error || 'Hubo un error al procesar uno de los pedidos.');
                                        break;
                                    }
                                }
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

    </body>
</html>
