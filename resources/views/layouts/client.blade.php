<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body class="font-sans antialiased bg-white text-gray-900">
    <x-banner />
    <x-toast />

    {{-- Todo el body vive dentro del scope del carrito global --}}
    <div class="min-h-screen bg-white pb-20 sm:pb-0" x-data="globalCart()" x-init="initCart()"
        @add-to-cart.window="addToCart($event.detail)">

        {{-- Nav de Livewire --}}
        @livewire('client-navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">
                {{ $header }}
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        {{-- ============================================================ --}}
        {{-- CART DRAWER GLOBAL (agrupado por negocio)                    --}}
        {{-- ============================================================ --}}
        <div x-data="{ open: false }" @open-cart.window="open = true" @close-cart.window="open = false"
            @keydown.escape.window="open = false" class="relative z-50" role="dialog" aria-modal="true"
            aria-labelledby="cart-title" x-show="open" style="display: none;">
            {{-- Overlay --}}
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-900/40" @click="open = false"></div>

            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full sm:pl-16">
                <div x-show="open" x-transition:enter="transform transition ease-out duration-300"
                    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in duration-200"
                    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto w-screen max-w-md">
                    <div class="flex h-full flex-col bg-white shadow-2xl">

                        {{-- Header --}}
                        <div class="flex items-center justify-between px-5 sm:px-6 h-16 border-b border-gray-100">
                            <h2 id="cart-title" class="font-display text-xl font-bold text-gray-900">
                                Tu carrito
                                <span x-show="totalItems > 0" class="ml-1 font-sans text-sm font-normal text-gray-500"
                                    x-text="totalItems + ' producto' + (totalItems > 1 ? 's' : '')"></span>
                            </h2>
                            <button type="button" @click="open = false" aria-label="Cerrar carrito"
                                class="-mr-2 p-2 rounded-full text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                                <x-icon name="x" />
                            </button>
                        </div>

                        {{-- Contenido --}}
                        <div class="flex-1 overflow-y-auto">

                            {{-- Carrito vacío --}}
                            <template x-if="cart.length === 0">
                                <div class="flex h-full flex-col items-center justify-center px-8 text-center">
                                    <div class="mb-5 flex size-16 items-center justify-center rounded-full bg-green-50 text-green-700">
                                        <x-icon name="bag" class="size-7" />
                                    </div>
                                    <p class="font-display text-lg font-bold text-gray-900">Tu carrito está vacío</p>
                                    <p class="mt-1 text-sm text-gray-500">Puedes agregar productos de varios negocios a la vez.</p>
                                    <button @click="open = false"
                                        class="mt-6 rounded-full border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-900 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                                        Seguir explorando
                                    </button>
                                </div>
                            </template>

                            {{-- Items agrupados por negocio --}}
                            <template x-if="cart.length > 0">
                                <div class="px-5 sm:px-6 divide-y divide-gray-100">
                                    <template x-for="group in groupedCart" :key="group.businessId">
                                        <section class="py-5">
                                            <h3 class="flex items-center gap-2 text-sm font-semibold text-gray-900">
                                                <x-icon name="store" class="size-4 text-green-700" />
                                                <span x-text="group.businessName"></span>
                                            </h3>
                                            <ul class="mt-3 space-y-4">
                                                <template x-for="(item, idx) in group.items" :key="item.id">
                                                    <li class="flex gap-3">
                                                        <div class="size-16 shrink-0 overflow-hidden rounded-xl bg-gray-100 flex items-center justify-center text-gray-300">
                                                            <template x-if="item.image">
                                                                <img :src="item.image" :alt="item.name" class="size-full object-cover">
                                                            </template>
                                                            <template x-if="!item.image">
                                                                <x-icon name="photo" class="size-6" />
                                                            </template>
                                                        </div>
                                                        <div class="flex min-w-0 flex-1 flex-col justify-between">
                                                            <div class="flex justify-between gap-3 text-sm">
                                                                <span x-text="item.name" class="font-medium text-gray-900 leading-snug"></span>
                                                                <span class="font-semibold text-gray-900 tabular-nums whitespace-nowrap"
                                                                    x-text="'$' + (item.price * item.quantity).toFixed(2)"></span>
                                                            </div>
                                                            <div class="flex items-center justify-between">
                                                                <div class="inline-flex items-center rounded-full border border-gray-200">
                                                                    <button type="button" aria-label="Quitar uno"
                                                                        @click="updateQuantityByIndex(item.cartIndex, item.quantity - 1)"
                                                                        class="p-1.5 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-50">
                                                                        <x-icon name="minus" class="size-4" />
                                                                    </button>
                                                                    <span class="w-7 text-center text-sm font-semibold tabular-nums" x-text="item.quantity"></span>
                                                                    <button type="button" aria-label="Agregar uno"
                                                                        @click="updateQuantityByIndex(item.cartIndex, item.quantity + 1)"
                                                                        class="p-1.5 text-gray-600 hover:text-gray-900 rounded-full hover:bg-gray-50">
                                                                        <x-icon name="plus" class="size-4" />
                                                                    </button>
                                                                </div>
                                                                <button type="button" @click="removeFromCartByIndex(item.cartIndex)"
                                                                    class="text-sm text-gray-500 hover:text-red-600 transition-colors">
                                                                    Quitar
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </template>
                                            </ul>
                                        </section>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Footer con total y checkout --}}
                        <template x-if="cart.length > 0">
                            <div class="border-t border-gray-100 px-5 sm:px-6 py-5">
                                <div class="flex items-baseline justify-between">
                                    <span class="text-sm text-gray-600">Total</span>
                                    <span x-text="'$' + totalPrice.toFixed(2)"
                                        class="font-display text-2xl font-bold text-gray-900 tabular-nums"></span>
                                </div>
                                <p x-show="groupedCart.length > 1" class="mt-1 text-xs text-gray-500"
                                    x-text="'Se creará un pedido para cada uno de los ' + groupedCart.length + ' negocios.'"></p>
                                <p x-show="checkoutError" x-text="checkoutError" role="alert"
                                    class="mt-3 rounded-xl bg-red-50 p-3 text-sm text-red-700"></p>
                                <button @click="checkout()" :disabled="isCheckingOut"
                                    class="mt-4 w-full flex items-center justify-center gap-2 rounded-full bg-green-600 px-6 py-3.5 text-base font-semibold text-white hover:bg-green-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                                    <span x-show="!isCheckingOut">Realizar pedido</span>
                                    <span x-show="isCheckingOut" class="flex items-center gap-2">
                                        <svg class="animate-spin size-4" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        Enviando pedido
                                    </span>
                                </button>
                            </div>
                        </template>

                    </div>
                </div>
            </div>
        </div>

        {{-- Barra de pestañas inferior (móvil) --}}
        @php
            $tabs = [
                ['label' => 'Inicio', 'icon' => 'home', 'route' => 'store', 'active' => request()->routeIs('store')],
                ['label' => 'Negocios', 'icon' => 'store', 'route' => 'store.businesses', 'active' => request()->routeIs('store.business*')],
                ['label' => 'Favoritos', 'icon' => 'heart', 'route' => 'store.favorites', 'active' => request()->routeIs('store.favorites*')],
                ['label' => 'Pedidos', 'icon' => 'receipt', 'route' => 'store.orders', 'active' => request()->routeIs('store.orders*')],
            ];
        @endphp
        <nav class="sm:hidden fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur pb-[env(safe-area-inset-bottom)]"
            aria-label="Navegación principal">
            <ul class="grid grid-cols-4">
                @foreach ($tabs as $tab)
                    <li>
                        <a href="{{ route($tab['route']) }}" wire:navigate
                            @if ($tab['active']) aria-current="page" @endif
                            @class([
                                'flex flex-col items-center gap-1 pt-2.5 pb-2 text-[11px] font-medium transition-colors',
                                'text-green-700' => $tab['active'],
                                'text-gray-500 hover:text-gray-900' => !$tab['active'],
                            ])>
                            <x-icon :name="$tab['icon']" class="size-6" />
                            {{ $tab['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

    </div>{{-- /globalCart scope --}}

    @stack('modals')

    @livewireScripts

    <script>
        window.globalCart = function() {
            return {
                cart: [],
                storageKey: 'cimafood_global_cart',
                isCheckingOut: false,
                checkoutError: '',
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
                        groups[key].items.push({
                            ...item,
                            cartIndex: index
                        });
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
                        window.dispatchEvent(new CustomEvent('cart-count-updated', {
                            detail: {
                                count: this.totalItems
                            }
                        }));
                    });
                },

                saveCart() {
                    localStorage.setItem(this.storageKey, JSON.stringify(this.cart));
                    window.dispatchEvent(new CustomEvent('cart-count-updated', {
                        detail: {
                            count: this.totalItems
                        }
                    }));
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
                    this.$dispatch('toast-show', {
                        message: 'Producto agregado al carrito'
                    });
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
                /**
                 * Checkout: envía el carrito completo en una sola petición.
                 * El servidor crea un pedido por negocio dentro de una transacción,
                 * así que o se crean todos o ninguno (no hay pedidos duplicados al reintentar).
                 */
                async checkout() {
                    if (this.cart.length === 0 || this.isCheckingOut) return;
                    this.isCheckingOut = true;
                    this.checkoutError = '';

                    try {
                        const response = await fetch(this.checkoutUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': this.csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                items: this.cart.map(item => ({
                                    id: item.id,
                                    quantity: item.quantity
                                }))
                            })
                        });

                        if (response.ok) {
                            this.cart = [];
                            this.saveCart();
                            window.location.href = '{{ route('store.orders') }}';
                            return;
                        }

                        const data = await response.json().catch(() => ({}));
                        this.checkoutError = data.errors ?
                            Object.values(data.errors).flat().join(' ') :
                            'No se pudo enviar el pedido. Intenta de nuevo en un momento.';
                    } catch (error) {
                        console.error(error);
                        this.checkoutError = 'No hay conexión. Revisa tu internet e intenta de nuevo.';
                    } finally {
                        this.isCheckingOut = false;
                    }
                }
            };
        };
    </script>

</body>

</html>
