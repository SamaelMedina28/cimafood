<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full p-2">
            <h1 class="text-lg font-bold text-gray-900 truncate">Mis Pedidos</h1>
        </div>
    </x-slot>

    <div class="pb-24 min-h-screen px-4 py-6 max-w-5xl mx-auto">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
            <span class="text-2xl text-emerald-500"></span> Historial de Pedidos
        </h2>

        @if($orders->count() > 0)
            <div class="space-y-4">
                @foreach($orders as $order)
                    <div class="bg-white rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-gray-50 pb-4 mb-4 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full overflow-hidden bg-gray-100 flex-shrink-0 border border-gray-200">
                                    @if($order->business && $order->business->logo)
                                        <img src="{{ str_starts_with($order->business->logo, 'http') ? $order->business->logo : asset('storage/' . $order->business->logo) }}" alt="{{ $order->business->name }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-emerald-50 flex items-center justify-center text-lg font-bold text-emerald-600">
                                            {{ $order->business ? strtoupper(substr($order->business->name, 0, 1)) : '?' }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-gray-900">{{ $order->business->name ?? 'Negocio Eliminado' }}</h3>
                                    <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                </div>
                            </div>
                            <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2">
                                <span class="text-sm font-bold {{ $order->status === 'pending' ? 'text-yellow-600 bg-yellow-50' : ($order->status === 'completed' ? 'text-green-600 bg-green-50' : 'text-gray-600 bg-gray-50') }} px-3 py-1 rounded-full uppercase tracking-wider">
                                    {{ $order->status === 'pending' ? 'Pendiente' : ($order->status === 'completed' ? 'Completado' : $order->status) }}
                                </span>
                                <span class="text-lg font-extrabold text-green-600">${{ number_format($order->total_price, 2) }}</span>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-sm font-semibold text-gray-700">Artículos:</h4>
                            <ul class="space-y-2">
                                @foreach($order->products as $product)
                                    <li class="flex justify-between items-center text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-900">{{ $product->pivot->quantity }}x</span>
                                            <span class="text-gray-600">{{ $product->name }}</span>
                                        </div>
                                        <span class="text-gray-500 font-medium">${{ number_format($product->pivot->subtotal, 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        @if($order->business)
                            <div class="mt-5 pt-4 border-t border-gray-50 flex justify-end">
                                <a href="{{ route('store.business', $order->business->id) }}" wire:navigate class="px-5 py-2 rounded-full bg-green-500 hover:bg-green-600 text-white font-semibold text-sm shadow-sm transition-colors transform active:scale-95 flex items-center gap-2">
                                    Volver a pedir
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl p-8 text-center border border-gray-100 border-dashed mt-8 max-w-md mx-auto">
                <div class="text-6xl mb-4 opacity-75">🍽️</div>
                <h3 class="text-xl font-bold text-gray-800">No tienes pedidos aún</h3>
                <p class="text-sm text-gray-500 mt-2">Parece que aún no has realizado ninguna compra. ¡Explora los negocios y disfruta de la comida!</p>
                <a href="{{ route('store') }}" wire:navigate class="inline-block mt-6 px-8 py-3 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-colors shadow-md hover:shadow-lg transform active:scale-95">
                    Hacer mi primer pedido
                </a>
            </div>
        @endif
    </div>
</x-client-layout>
