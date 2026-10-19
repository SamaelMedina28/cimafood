<nav class="sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-gray-100">
    @php
        $links = [
            ['label' => 'Inicio', 'route' => 'store', 'active' => request()->routeIs('store')],
            ['label' => 'Negocios', 'route' => 'store.businesses', 'active' => request()->routeIs('store.business*')],
            ['label' => 'Favoritos', 'route' => 'store.favorites', 'active' => request()->routeIs('store.favorites*')],
            ['label' => 'Mis pedidos', 'route' => 'store.orders', 'active' => request()->routeIs('store.orders*')],
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center gap-4 lg:gap-8">
            <!-- Logo -->
            <a href="{{ route('store') }}" wire:navigate class="flex shrink-0 items-center gap-2">
                <img src="{{ asset('Logo-uabc.png') }}" alt="" class="size-9 object-contain">
                <span class="font-display text-xl font-extrabold tracking-tight text-gray-900">Cima<span
                        class="text-green-600">Food</span></span>
            </a>

            <!-- Navigation Links -->
            <div class="hidden sm:flex h-full items-center gap-6">
                @foreach ($links as $link)
                    <x-nav-link href="{{ route($link['route']) }}" :active="$link['active']" wire:navigate class="h-full">
                        {{ $link['label'] }}
                    </x-nav-link>
                @endforeach
            </div>

            <div class="ml-auto flex items-center gap-1 sm:gap-2">
                <!-- Búsqueda (escritorio) -->
                <form action="{{ route('store.products') }}" method="GET" role="search" class="hidden md:block">
                    <label for="nav-search" class="sr-only">Buscar productos</label>
                    <div class="relative">
                        <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                        <input id="nav-search" type="search" name="q" value="{{ request()->routeIs('store.products') ? request('q') : '' }}"
                            placeholder="Buscar comida"
                            class="w-44 lg:w-56 rounded-full border-gray-200 bg-gray-50 py-2 pl-9 pr-3 text-sm placeholder:text-gray-400 focus:border-green-600 focus:bg-white focus:ring-green-600">
                    </div>
                </form>

                <!-- Búsqueda (móvil) -->
                <a href="{{ route('store.products') }}" wire:navigate aria-label="Buscar"
                    class="md:hidden p-2 rounded-full text-gray-700 hover:bg-gray-100">
                    <x-icon name="search" class="size-6" />
                </a>

                <!-- Carrito -->
                <button type="button" aria-label="Abrir carrito"
                    class="relative p-2 rounded-full text-gray-700 hover:bg-gray-100 transition-colors"
                    x-data="{
                        count: 0,
                        init() {
                            try {
                                let c = JSON.parse(localStorage.getItem('cimafood_global_cart') || '[]');
                                this.count = c.reduce((s, i) => s + i.quantity, 0);
                            } catch (e) {}
                            window.addEventListener('cart-count-updated', e => this.count = e.detail.count);
                        }
                    }"
                    @click="window.dispatchEvent(new CustomEvent('open-cart'))">
                    <x-icon name="bag" class="size-6" />
                    <span x-show="count > 0" x-text="count" x-cloak
                        class="absolute top-0.5 right-0.5 inline-flex min-w-5 h-5 px-1 items-center justify-center rounded-full bg-green-600 text-[11px] font-bold text-white ring-2 ring-white tabular-nums"></span>
                </button>

                <!-- Cuenta -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button type="button"
                            class="ml-1 flex items-center gap-1 rounded-full p-1 text-sm text-gray-700 hover:bg-gray-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 transition">
                            <span class="flex size-8 items-center justify-center rounded-full bg-green-50 font-display font-bold text-green-700">
                                {{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <x-icon name="chevron-down" class="hidden sm:block size-4 text-gray-400" />
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link href="{{ route('profile.show') }}">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        @if (Auth::user()->is_vendor)
                            <x-dropdown-link href="{{ route('dashboard') }}">
                                Panel de mi negocio
                            </x-dropdown-link>
                        @endif

                        <div class="border-t border-gray-100"></div>

                        <form method="POST" action="{{ route('logout') }}" x-data>
                            @csrf
                            <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
