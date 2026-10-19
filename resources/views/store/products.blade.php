<x-client-layout>
    <x-slot name="header">
        @if ($search)
            <p class="text-sm font-medium text-gray-500">Resultados de búsqueda</p>
            <h1 class="mt-1 font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">
                «{{ $search }}»</h1>
        @else
            <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Productos</h1>
            <p class="mt-1 text-gray-500">Ordenados por los más vendidos.</p>
        @endif

        <form action="{{ route('store.products') }}" method="GET" role="search" class="mt-5 md:hidden">
            <label for="page-search" class="sr-only">Buscar productos</label>
            <div class="relative">
                <x-icon name="search" class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 size-5 text-gray-400" />
                <input id="page-search" type="search" name="q" value="{{ $search }}" placeholder="Tacos, sushi, hamburguesa"
                    class="w-full rounded-full border-gray-200 bg-gray-50 py-3 pl-11 pr-4 placeholder:text-gray-400 focus:border-green-600 focus:bg-white focus:ring-green-600">
            </div>
        </form>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        @if ($products->count() > 0)
            @if ($search)
                <p class="mb-6 text-sm text-gray-500">
                    {{ $products->total() }} {{ $products->total() === 1 ? 'producto' : 'productos' }}
                    <a href="{{ route('store.products') }}" wire:navigate class="ml-2 font-semibold text-green-700 hover:text-green-800">Quitar búsqueda</a>
                </p>
            @endif

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-10 sm:gap-x-6">
                @foreach ($products as $product)
                    <x-store.product-card :product="$product" />
                @endforeach
            </div>

            @if ($products->hasPages())
                <div class="mt-12">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="rounded-2xl bg-gray-50 px-6 py-16 text-center">
                <x-icon name="search" class="mx-auto size-10 text-gray-300" />
                @if ($search)
                    <h2 class="mt-4 font-display text-lg font-bold text-gray-900">Nada coincide con «{{ $search }}»</h2>
                    <p class="mt-1 text-sm text-gray-500">Prueba con otra palabra, por ejemplo el nombre del platillo.</p>
                    <a href="{{ route('store.products') }}" wire:navigate
                        class="mt-6 inline-flex rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                        Ver todos los productos</a>
                @else
                    <h2 class="mt-4 font-display text-lg font-bold text-gray-900">Aún no hay productos</h2>
                    <p class="mt-1 text-sm text-gray-500">Cuando los negocios publiquen su menú aparecerá aquí.</p>
                @endif
            </div>
        @endif
    </div>
</x-client-layout>
