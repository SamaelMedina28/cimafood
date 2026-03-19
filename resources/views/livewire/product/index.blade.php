<div>
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
                    class="w-full pl-10 pr-4 py-2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
            </div>

            <!-- Select -->
            <div class="w-full md:w-1/3">
                <select wire:model.live="businessId"
                    class="w-full py-2 px-3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
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
                <p class="mt-2 text-sm text-gray-500 max-w-sm">No se encontraron productos con los filtros actuales.</p>
            </div>
        @else
            <ul class="divide-y divide-gray-100">
                @foreach ($products as $product)
                    <li class="p-5 sm:p-6 hover:bg-gray-50/50 transition-colors duration-200 group">
                        <div class="flex items-center space-x-5">
                            <div class="flex-shrink-0">
                                @if ($product->image_path)
                                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}"
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
                                <div class="flex items-center justify-between">
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
                                    <div class="flex items-center space-x-3 text-lg font-bold text-gray-900">
                                        ${{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                <div class="mt-1.5 flex flex-wrap items-center text-sm text-gray-500 gap-y-1 gap-x-4">
                                    @if ($product->business)
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
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
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            {{ $product->quantity }} disponibles
                                        </span>
                                    @endif
                                    <span class="flex items-center truncate max-w-[200px] sm:max-w-xs">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $product->description ? Str::limit($product->description, 50) : 'Sin descripción' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            @endif
        </div>
        {{ $products->links() }}
</div>
