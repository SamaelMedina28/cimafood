<div>
    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <!-- Filters -->
        <div class="mb-6 p-4 border border-gray-200 rounded-lg">

            <div class="flex flex-col md:flex-row md:items-center gap-4">

                <!-- Buscador -->
                <div class="w-full md:flex-1 relative">
                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                        <!-- icono -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                        </svg>
                    </span>

                    <x-input type="text" wire:model.live="productName" placeholder="Buscar producto..."
                        class="w-full pl-10 pr-4 py-2 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" />
                </div>

                <!-- Select -->
                <div class="w-full md:w-1/3">
                    <select wire:model.live="businessId"
                        class="w-full py-2 px-3 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                        <option value="">Todos los negocios</option>
                        @foreach ($businesses as $business)
                            <option value="{{ $business->id }}">{{ $business->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            @if ($products->count() === 0)
                <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                    <div
                        class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 tracking-tight">Aún no hay productos</h3>
                    <p class="mt-2 text-sm text-gray-500 max-w-sm">No se encontraron productos con los filtros
                        actuales.</p>
                </div>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($products as $product)
                        <li class="p-5 sm:p-6 hover:bg-gray-50/50 transition-colors duration-200 group">
                            <div class="flex items-start sm:items-center space-x-4 sm:space-x-5">
                                <div class="flex-shrink-0 mt-1 sm:mt-0">
                                    @if ($product->image_path)
                                        <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                                    @else
                                        <div
                                            class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 flex items-center justify-center shadow-sm">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-0">
                                        <p
                                            class="text-base font-semibold text-gray-900 truncate tracking-tight group-hover:text-green-700 transition-colors">
                                            @if (isset($product->status) && $product->status === 'available')
                                                <span
                                                    class="inline-flex items-center px-1.5 py-1.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                </span>
                                            @elseif(isset($product->status) && $product->status !== 'available')
                                                <span
                                                    class="inline-flex items-center px-1.5 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                </span>
                                            @endif
                                            {{ $product->name }}
                                        </p>
                                        <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                                            <div class="text-lg font-bold text-gray-900">
                                                ${{ number_format($product->price, 2) }}
                                            </div>
                                            <div class="flex flex-wrap items-center gap-2">
                                                {{-- Botón Ver --}}
                                                <a href="{{ route('product.show', $product) }}" wire:navigate
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-slate-600 bg-white rounded-full hover:bg-slate-100 hover:text-slate-900 hover:border-slate-200 transition-all duration-150">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    Ver
                                                </a>

                                                {{-- Botón Editar --}}
                                                <a href="{{ route('product.edit', $product) }}" wire:navigate
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-yellow-500 rounded-full shadow-sm hover:bg-yellow-600 active:bg-yellow-700 transition-all duration-150">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                        stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                    Editar
                                                </a>
                                                {{-- Botón Eliminar --}}
                                                <x-danger-button wire:click="openModal({{ $product->id }})" class="!rounded-full">
                                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="white"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Eliminar
                                                </x-danger-button>
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="mt-1.5 flex flex-wrap items-center text-sm text-gray-500 gap-y-1 gap-x-4">
                                        @if ($product->business)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                    </path>
                                                </svg>
                                                {{ $product->business->name }}
                                            </span>
                                        @endif
                                        @if ($product->quantity !== null)
                                            <span class="flex items-center">
                                                <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                                {{ $product->quantity }} disponibles
                                            </span>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <x-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">Eliminar producto</h2>
        </x-slot>
        <x-slot name="content">
            <p class="text-sm text-gray-500">¿Estás seguro de que deseas eliminar este producto? Esta acción no se puede deshacer.</p>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isOpen', false)" class="mr-2 !rounded-full">
                Cancelar
            </x-secondary-button>
            <x-danger-button wire:click="delete" class="!rounded-full">
                Eliminar
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
