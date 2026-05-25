<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('product.index') }}" wire:navigate
                class="inline-flex items-center gap-1.5 text-sm font-medium text-slate-600 bg-white rounded-full">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Volver
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
                {{ $product->name }}
            </h2>
        </div>
    </x-slot>
    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end my-5">
                {{-- Editar --}}
                <a href="{{ route('product.edit', $product) }}" wire:navigate
                    class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-white bg-yellow-500 rounded-full shadow-sm hover:bg-yellow-600 active:bg-yellow-700 transition-all duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Editar producto
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        {{-- Imagen --}}
                        <div
                            class="flex items-center justify-center bg-gray-50 rounded-lg border border-gray-100 overflow-hidden min-h-[300px]">
                            @if ($product->image_path)
                                <img src="{{ str_starts_with($product->image_path, 'http') ? $product->image_path : asset('storage/' . $product->image_path) }}"
                                    class="w-full h-full object-cover">
                            @else
                                <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            @endif
                        </div>

                        {{-- Detalles --}}
                        <div class="flex flex-col">
                            <div class="mb-6">
                                <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ $product->name }}</h3>
                                <p class="text-4xl font-bold text-emerald-600">${{ number_format($product->price, 2) }}
                                </p>
                            </div>

                            <div class="space-y-6 flex-1">
                                <div>
                                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                        Descripción</h4>
                                    <p class="text-gray-700 leading-relaxed text-lg">
                                        {{ $product->description ?: 'No hay descripción disponible para este producto.' }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Estado
                                        </h4>
                                        @if (isset($product->status) && $product->status === 'available')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-2"></span>
                                                Disponible
                                            </span>
                                        @elseif(isset($product->status) && $product->status !== 'available')
                                            <span
                                                class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-2"></span>
                                                No disponible
                                            </span>
                                        @else
                                            <span class="text-sm font-medium text-gray-900">No especificado</span>
                                        @endif
                                    </div>

                                    <div>
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                            Inventario</h4>
                                        <p class="text-gray-900 font-medium text-lg flex items-center">
                                            @if ($product->quantity !== null)
                                                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                    </path>
                                                </svg>
                                                {{ $product->quantity }} <span
                                                    class="text-gray-500 text-sm ml-1">unidades</span>
                                            @else
                                                <span class="text-gray-500">No especificado</span>
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-span-2">
                                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">
                                            Negocio Asociado</h4>
                                        <div class="flex items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                            @if ($product->business)
                                                <div
                                                    class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center mr-3 shadow-sm">
                                                    <svg class="w-5 h-5 text-gray-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="1.5"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                        </path>
                                                    </svg>
                                                </div>
                                                <span
                                                    class="text-gray-900 font-medium text-base">{{ $product->business->name }}</span>
                                            @else
                                                <span class="text-gray-500">No asignado</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
