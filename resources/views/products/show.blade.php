<x-app-layout>
    @php $available = $product->status === 'available'; @endphp

    <x-slot name="header">
        <x-admin.header :title="$product->name" :description="$product->business?->name"
            :back="route('product.index')" back-label="Productos">
            <x-slot name="actions">
                <a href="{{ route('product.edit', $product) }}" wire:navigate
                    class="inline-flex items-center gap-1.5 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                    <x-icon name="pencil" class="size-4" />
                    Editar
                </a>
            </x-slot>
        </x-admin.header>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-10">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <div class="aspect-square overflow-hidden rounded-3xl bg-gray-100">
                @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="size-full object-cover">
                @else
                    <div class="flex size-full items-center justify-center text-gray-300"><x-icon name="photo" class="size-12" /></div>
                @endif
            </div>

            <div>
                <p class="font-display text-4xl font-bold text-gray-900 tabular-nums">${{ number_format($product->price, 2) }}</p>
                <p class="mt-4 text-lg text-gray-600">{{ $product->description ?: 'Sin descripción.' }}</p>

                <dl class="mt-8 grid grid-cols-2 gap-6 border-t border-gray-100 pt-6">
                    <div>
                        <dt class="text-sm text-gray-500">Disponibilidad</dt>
                        <dd class="mt-1">
                            <span @class([
                                'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-sm font-medium ring-1 ring-inset',
                                'bg-green-50 text-green-800 ring-green-200' => $available,
                                'bg-gray-100 text-gray-600 ring-gray-200' => !$available,
                            ])>
                                <span @class(['size-1.5 rounded-full', 'bg-green-600' => $available, 'bg-gray-400' => !$available])></span>
                                {{ $available ? 'Disponible' : 'No disponible' }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm text-gray-500">Stock</dt>
                        <dd @class(['mt-1 font-display text-2xl font-bold tabular-nums', 'text-red-600' => $product->quantity <= 0, 'text-gray-900' => $product->quantity > 0])>
                            {{ $product->quantity <= 0 ? 'Agotado' : $product->quantity . ' unidades' }}
                        </dd>
                    </div>
                    @if ($product->business)
                        <div class="col-span-2">
                            <dt class="text-sm text-gray-500">Negocio</dt>
                            <dd class="mt-2">
                                <a href="{{ route('business.show', $product->business) }}" wire:navigate
                                    class="inline-flex items-center gap-3 rounded-full border border-gray-200 py-1.5 pl-1.5 pr-4 hover:border-gray-300 hover:bg-gray-50">
                                    <span class="size-8 overflow-hidden rounded-full bg-green-50">
                                        @if ($product->business->logo_url)
                                            <img src="{{ $product->business->logo_url }}" alt="" class="size-full object-cover">
                                        @endif
                                    </span>
                                    <span class="font-medium text-gray-900">{{ $product->business->name }}</span>
                                </a>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
