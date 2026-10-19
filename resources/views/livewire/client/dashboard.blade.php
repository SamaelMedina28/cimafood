<div>
    @php
        $categories = [
            ['label' => 'Hamburguesas', 'q' => 'hamburguesa', 'image' => 'example_images/products/hamburgesa/img1.jpg'],
            ['label' => 'Tacos', 'q' => 'taco', 'image' => 'example_images/products/tacos/img1.jpg'],
            ['label' => 'Burritos', 'q' => 'burrito', 'image' => 'example_images/products/burritos/img1.jpg'],
            ['label' => 'Quesadillas', 'q' => 'quesadilla', 'image' => 'example_images/products/tacos/img4.jpg'],
            ['label' => 'Sushi', 'q' => 'roll', 'image' => 'example_images/products/sushi/img1.jpg'],
        ];

        $filters = [
            ['id' => 'todos', 'label' => 'Todos', 'icon' => 'grid'],
            ['id' => 'abierto', 'label' => 'Abiertos ahora', 'icon' => 'clock'],
            ['id' => 'populares', 'label' => 'Más pedidos', 'icon' => 'fire'],
            ['id' => 'economico', 'label' => 'Menos de $50', 'icon' => 'tag'],
        ];

        $titles = [
            'todos' => 'Negocios',
            'abierto' => 'Abiertos ahora',
            'populares' => 'Los más pedidos',
            'economico' => 'Productos de menos de $50',
        ];
    @endphp

    {{-- ===================== CATEGORÍAS ===================== --}}
    <section class="mt-10" aria-labelledby="categories-title">
        <h2 id="categories-title" class="font-display text-xl sm:text-2xl font-bold text-gray-900">Categorías</h2>
        <div class="mt-4 -mx-4 px-4 sm:mx-0 sm:px-0 flex gap-4 sm:gap-6 overflow-x-auto scrollbar-none">
            @foreach ($categories as $cat)
                <a href="{{ route('store.products', ['q' => $cat['q']]) }}" wire:navigate
                    class="group flex w-20 sm:w-24 shrink-0 flex-col items-center gap-2 focus:outline-none">
                    <span class="size-20 sm:size-24 overflow-hidden rounded-full bg-gray-100 ring-1 ring-gray-200 transition group-hover:ring-2 group-hover:ring-green-600 group-focus-visible:ring-2 group-focus-visible:ring-green-600">
                        <img src="{{ asset($cat['image']) }}" alt="" loading="lazy" class="size-full object-cover">
                    </span>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">{{ $cat['label'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ===================== FILTROS + RESULTADOS ===================== --}}
    <section class="mt-10" aria-labelledby="filtered-title">
        <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-4">
            <h2 id="filtered-title" class="font-display text-xl sm:text-2xl font-bold text-gray-900">
                {{ $titles[$filter] ?? 'Negocios' }}
            </h2>
            @if ($filter !== 'economico')
                <a href="{{ route('store.businesses') }}" wire:navigate
                    class="text-sm font-semibold text-green-700 hover:text-green-800">Ver todos los negocios</a>
            @endif
        </div>

        <div class="mt-4 -mx-4 px-4 sm:mx-0 sm:px-0 flex gap-2 overflow-x-auto scrollbar-none" role="group" aria-label="Filtrar">
            @foreach ($filters as $filterItem)
                @php $active = $filterItem['id'] === $filter; @endphp
                <button type="button" wire:click="setFilter('{{ $filterItem['id'] }}')" aria-pressed="{{ $active ? 'true' : 'false' }}"
                    @class([
                        'inline-flex shrink-0 items-center gap-1.5 rounded-full border px-4 py-2 text-sm font-medium whitespace-nowrap transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-2',
                        'border-green-600 bg-green-600 text-white' => $active,
                        'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:text-gray-900' => !$active,
                    ])>
                    <x-icon :name="$filterItem['icon']" class="size-4" />
                    {{ $filterItem['label'] }}
                </button>
            @endforeach
        </div>

        <div class="mt-6" wire:loading.class="opacity-50" wire:target="setFilter">
            @if ($filter === 'economico')
                @if ($products->count() > 0)
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-8 sm:gap-x-6">
                        @foreach ($products as $product)
                            <x-store.product-card :product="$product" wire:key="cheap-{{ $product->id }}" />
                        @endforeach
                    </div>
                @else
                    <p class="rounded-2xl bg-gray-50 px-6 py-10 text-center text-sm text-gray-500">
                        Ahora mismo no hay productos de menos de $50.
                    </p>
                @endif
            @else
                @if ($businesses->count() > 0)
                    <div class="-mx-4 px-4 sm:mx-0 sm:px-0 flex gap-4 sm:gap-6 overflow-x-auto scrollbar-none snap-x">
                        @foreach ($businesses as $business)
                            <x-store.business-card :business="$business" wire:key="biz-{{ $business->id }}"
                                class="w-72 sm:w-80 shrink-0 snap-start" />
                        @endforeach
                    </div>
                @else
                    <p class="rounded-2xl bg-gray-50 px-6 py-10 text-center text-sm text-gray-500">
                        @if ($filter === 'abierto')
                            No hay negocios abiertos a esta hora. Revisa más tarde o prueba otro filtro.
                        @elseif ($filter === 'populares')
                            Todavía no hay pedidos suficientes para mostrar los más pedidos.
                        @else
                            Aún no hay negocios registrados.
                        @endif
                    </p>
                @endif
            @endif
        </div>
    </section>

    {{-- ===================== PRODUCTOS DESTACADOS ===================== --}}
    <section class="mt-12" aria-labelledby="best-title">
        <div class="flex items-end justify-between gap-6">
            <h2 id="best-title" class="font-display text-xl sm:text-2xl font-bold text-gray-900">Lo más vendido</h2>
            <a href="{{ route('store.products') }}" wire:navigate
                class="text-sm font-semibold text-green-700 hover:text-green-800">Ver todos los productos</a>
        </div>

        @if ($featuredProducts->count() > 0)
            <div class="mt-6 grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-8 sm:gap-x-6">
                @foreach ($featuredProducts->take(8) as $product)
                    <x-store.product-card :product="$product" wire:key="best-{{ $product->id }}" />
                @endforeach
            </div>
        @else
            <p class="mt-6 rounded-2xl bg-gray-50 px-6 py-10 text-center text-sm text-gray-500">
                Aún no hay ventas registradas.
            </p>
        @endif
    </section>
</div>
