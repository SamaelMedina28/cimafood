<div class="space-y-6">
    <section class="rounded-2xl border border-gray-200 overflow-hidden">
        {{-- Filtros --}}
        <div class="flex flex-col gap-3 border-b border-gray-100 p-4 sm:p-5 md:flex-row">
            <div class="relative flex-1">
                <label for="product-search" class="sr-only">Buscar producto</label>
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                <x-input id="product-search" type="search" wire:model.live.debounce.300ms="productName"
                    placeholder="Buscar por nombre" class="w-full pl-9 text-sm" />
            </div>
            @if ($businesses->count() > 1)
                <label for="product-business" class="sr-only">Negocio</label>
                <select id="product-business" wire:model.live="businessId"
                    class="w-full md:w-60 rounded-lg border-gray-200 text-sm shadow-sm focus:border-green-600 focus:ring-green-600">
                    <option value="">Todos los negocios</option>
                    @foreach ($businesses as $business)
                        <option value="{{ $business->id }}">{{ $business->name }}</option>
                    @endforeach
                </select>
            @endif
        </div>

        <div wire:loading.class="opacity-60" wire:target="productName, businessId, gotoPage, nextPage, previousPage">
            @if ($products->count() === 0)
                <x-admin.empty icon="tag" :title="$productName || $businessId ? 'Ningún producto coincide' : 'Aún no hay productos'">
                    @if ($productName || $businessId)
                        Prueba con otro nombre o con otro negocio.
                    @else
                        Agrega tu primer producto para que aparezca en el menú.
                    @endif
                </x-admin.empty>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="border-b border-gray-100 text-left text-gray-500">
                            <tr>
                                <th scope="col" class="px-5 sm:px-6 py-3 font-medium">Producto</th>
                                <th scope="col" class="px-3 py-3 font-medium text-right">Precio</th>
                                <th scope="col" class="px-3 py-3 font-medium text-right">Stock</th>
                                <th scope="col" class="px-3 py-3 font-medium hidden sm:table-cell">Estado</th>
                                <th scope="col" class="px-5 sm:px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($products as $product)
                                @php $available = $product->status === 'available'; @endphp
                                <tr wire:key="product-{{ $product->id }}" class="hover:bg-gray-50/70">
                                    <td class="px-5 sm:px-6 py-3">
                                        <a href="{{ route('product.show', $product) }}" wire:navigate class="group flex items-center gap-3">
                                            <span class="size-12 shrink-0 overflow-hidden rounded-xl bg-gray-100">
                                                @if ($product->image_url)
                                                    <img src="{{ $product->image_url }}" alt="" @class(['size-full object-cover', 'grayscale opacity-60' => !$available])>
                                                @else
                                                    <span class="flex size-full items-center justify-center text-gray-300"><x-icon name="photo" /></span>
                                                @endif
                                            </span>
                                            <span class="min-w-0">
                                                <span class="block truncate max-w-[16rem] font-medium text-gray-900 group-hover:text-green-700">{{ $product->name }}</span>
                                                <span class="block truncate text-xs text-gray-500">{{ $product->business->name ?? '' }}</span>
                                            </span>
                                        </a>
                                    </td>
                                    <td class="px-3 py-3 text-right font-semibold text-gray-900 tabular-nums whitespace-nowrap">${{ number_format($product->price, 2) }}</td>
                                    <td class="px-3 py-3 text-right tabular-nums whitespace-nowrap">
                                        @if ($product->quantity <= 0)
                                            <span class="font-medium text-red-600">Agotado</span>
                                        @elseif ($product->quantity <= 5)
                                            <span class="font-medium text-amber-700">{{ $product->quantity }}</span>
                                        @else
                                            <span class="text-gray-700">{{ $product->quantity }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-3 hidden sm:table-cell">
                                        <span @class([
                                            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset whitespace-nowrap',
                                            'bg-green-50 text-green-800 ring-green-200' => $available,
                                            'bg-gray-100 text-gray-600 ring-gray-200' => !$available,
                                        ])>
                                            <span @class(['size-1.5 rounded-full', 'bg-green-600' => $available, 'bg-gray-400' => !$available])></span>
                                            {{ $available ? 'Disponible' : 'No disponible' }}
                                        </span>
                                    </td>
                                    <td class="px-5 sm:px-6 py-3">
                                        <div class="flex justify-end gap-1">
                                            <a href="{{ route('product.edit', $product) }}" wire:navigate
                                                class="rounded-full px-3 py-1.5 font-semibold text-gray-900 hover:bg-gray-100">Editar</a>
                                            <button type="button" wire:click="openModal({{ $product->id }})"
                                                class="rounded-full px-3 py-1.5 font-semibold text-red-600 hover:bg-red-50">Eliminar</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>

    {{ $products->links() }}

    <x-dialog-modal wire:model="isOpen" maxWidth="md">
        <x-slot name="title">¿Eliminar este producto?</x-slot>
        <x-slot name="content">
            Dejará de aparecer en el menú y en el historial de pedidos. Esta acción no se puede deshacer.
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isOpen', false)">Conservar</x-secondary-button>
            <x-danger-button wire:click="delete">Eliminar producto</x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
