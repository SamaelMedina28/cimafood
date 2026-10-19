@php
    $user = Auth::user();
    $links = [];

    if ($user->is_vendor) {
        $pendingOrders = \App\Models\Order::whereIn('business_id', $user->businesses()->pluck('id'))
            ->where('status', 'pending')
            ->count();

        $links = [
            ['label' => 'Resumen', 'icon' => 'home', 'route' => 'dashboard', 'active' => request()->routeIs('dashboard')],
            ['label' => 'Pedidos', 'icon' => 'receipt', 'route' => 'order.index', 'active' => request()->routeIs('order*'), 'badge' => $pendingOrders],
            ['label' => 'Productos', 'icon' => 'tag', 'route' => 'product.index', 'active' => request()->routeIs('product*')],
            ['label' => 'Negocios', 'icon' => 'store', 'route' => 'business.index', 'active' => request()->routeIs('business*')],
        ];
    }
@endphp

<div x-data="{ open: false }" class="lg:w-64 lg:shrink-0">
    {{-- Barra superior (móvil y tablet) --}}
    <div class="lg:hidden sticky top-0 z-30 flex h-16 items-center justify-between border-b border-gray-100 bg-white/95 px-4 sm:px-6 backdrop-blur">
        <a href="{{ $user->is_vendor ? route('dashboard') : route('store') }}" wire:navigate class="flex items-center gap-2">
            <img src="{{ asset('Logo-uabc.png') }}" alt="" class="size-8 object-contain">
            <span class="font-display text-lg font-extrabold tracking-tight">Cima<span class="text-green-600">Food</span></span>
        </a>
        <button type="button" @click="open = !open" :aria-expanded="open" aria-label="Abrir menú"
            class="-mr-2 p-2 rounded-full text-gray-700 hover:bg-gray-100">
            <x-icon name="grid" class="size-6" x-show="!open" />
            <x-icon name="x" class="size-6" x-show="open" x-cloak />
        </button>
    </div>

    {{-- Barra lateral (escritorio) / menú desplegable (móvil) --}}
    <aside :class="{ '!flex flex-col': open }"
        class="hidden lg:flex lg:flex-col lg:fixed lg:inset-y-0 lg:w-64 border-b lg:border-b-0 lg:border-r border-gray-100 bg-gray-50/70">
        <a href="{{ $user->is_vendor ? route('dashboard') : route('store') }}" wire:navigate
            class="hidden lg:flex h-20 items-center gap-2 px-6">
            <img src="{{ asset('Logo-uabc.png') }}" alt="" class="size-9 object-contain">
            <span class="leading-tight">
                <span class="block font-display text-xl font-extrabold tracking-tight">Cima<span class="text-green-600">Food</span></span>
                <span class="block text-xs text-gray-500">Panel de negocio</span>
            </span>
        </a>

        <nav class="flex-1 px-3 py-3 lg:py-2 space-y-1" aria-label="Panel">
            @foreach ($links as $link)
                <a href="{{ route($link['route']) }}" wire:navigate
                    @if ($link['active']) aria-current="page" @endif
                    @class([
                        'flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-colors',
                        'bg-white text-gray-900 shadow-sm ring-1 ring-gray-200' => $link['active'],
                        'text-gray-600 hover:bg-white hover:text-gray-900' => !$link['active'],
                    ])>
                    <x-icon :name="$link['icon']" :class="$link['active'] ? 'size-5 text-green-600' : 'size-5 text-gray-400'" />
                    <span class="flex-1">{{ $link['label'] }}</span>
                    @if (!empty($link['badge']))
                        <span class="inline-flex min-w-5 h-5 items-center justify-center rounded-full bg-green-600 px-1.5 text-[11px] font-bold text-white tabular-nums"
                            title="{{ $link['badge'] }} pedidos pendientes">{{ $link['badge'] }}</span>
                    @endif
                </a>
            @endforeach

            <a href="{{ route('store') }}" wire:navigate
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-white hover:text-gray-900 transition-colors">
                <x-icon name="bag" class="size-5 text-gray-400" />
                {{ $user->is_vendor ? 'Ver la tienda' : 'Volver a la tienda' }}
            </a>
        </nav>

        {{-- Cuenta --}}
        <div class="border-t border-gray-200/70 p-3">
            <x-dropdown align="left" width="48" contentClasses="py-1 bg-white" dropdownClasses="bottom-full mb-2">
                <x-slot name="trigger">
                    <button type="button"
                        class="flex w-full items-center gap-3 rounded-xl px-3 py-2 text-left hover:bg-white transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                        <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-green-100 font-display font-bold text-green-800">
                            {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block truncate text-sm font-medium text-gray-900">{{ $user->name }}</span>
                            <span class="block truncate text-xs text-gray-500">{{ $user->email }}</span>
                        </span>
                        <x-icon name="chevron-down" class="size-4 text-gray-400" />
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link href="{{ route('profile.show') }}">
                        {{ __('Profile') }}
                    </x-dropdown-link>

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
    </aside>
</div>
