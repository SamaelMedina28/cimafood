<x-client-layout>
    <x-slot name="header">
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Mis pedidos</h1>
        <p class="mt-1 text-gray-500">Del más reciente al más antiguo.</p>
    </x-slot>

    @php
        $statuses = [
            'pending' => ['label' => 'Pendiente', 'class' => 'bg-amber-50 text-amber-800 ring-amber-200'],
            'completed' => ['label' => 'Completado', 'class' => 'bg-green-50 text-green-800 ring-green-200'],
            'cancelled' => ['label' => 'Cancelado', 'class' => 'bg-gray-100 text-gray-600 ring-gray-200'],
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        @if ($orders->count() > 0)
            <div class="max-w-3xl space-y-4">
                @foreach ($orders as $order)
                    @php $status = $statuses[$order->status] ?? ['label' => $order->status, 'class' => 'bg-gray-100 text-gray-600 ring-gray-200']; @endphp
                    <article class="rounded-2xl border border-gray-200 p-5 sm:p-6">
                        <header class="flex items-start gap-4">
                            <div class="size-12 shrink-0 overflow-hidden rounded-full bg-green-50 ring-1 ring-gray-200">
                                @if ($order->business?->logo_url)
                                    <img src="{{ $order->business->logo_url }}" alt="" class="size-full object-cover">
                                @else
                                    <span class="flex size-full items-center justify-center font-display font-bold text-green-700">
                                        {{ $order->business ? mb_strtoupper(mb_substr($order->business->name, 0, 1)) : '?' }}
                                    </span>
                                @endif
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <h2 class="font-semibold text-gray-900 truncate">
                                        {{ $order->business->name ?? 'Negocio eliminado' }}</h2>
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $status['class'] }}">
                                        {{ $status['label'] }}</span>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">
                                    Pedido #{{ $order->id }}, {{ $order->created_at->translatedFormat('j \d\e F, H:i') }}</p>
                            </div>
                            <p class="font-display text-lg font-bold text-gray-900 tabular-nums">
                                ${{ number_format($order->total_price, 2) }}</p>
                        </header>

                        <ul class="mt-5 divide-y divide-gray-100 border-t border-gray-100 text-sm">
                            @foreach ($order->products as $product)
                                <li class="flex items-center justify-between gap-4 py-2.5">
                                    <span class="text-gray-700">
                                        <span class="font-semibold text-gray-900 tabular-nums">{{ $product->pivot->quantity }}×</span>
                                        {{ $product->name }}
                                    </span>
                                    <span class="text-gray-500 tabular-nums">${{ number_format($product->pivot->subtotal, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>

                        @if ($order->business)
                            <div class="mt-4 flex justify-end">
                                <a href="{{ route('store.business', $order->business->id) }}" wire:navigate
                                    class="inline-flex items-center gap-1 rounded-full border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-900 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                                    Ir al negocio
                                    <x-icon name="chevron-right" class="size-4" />
                                </a>
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl bg-gray-50 px-6 py-16 text-center">
                <x-icon name="receipt" class="mx-auto size-10 text-gray-300" />
                <h2 class="mt-4 font-display text-lg font-bold text-gray-900">Todavía no has hecho pedidos</h2>
                <p class="mt-1 text-sm text-gray-500">Agrega productos al carrito y aquí podrás seguir su estado.</p>
                <a href="{{ route('store') }}" wire:navigate
                    class="mt-6 inline-flex rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                    Explorar comida</a>
            </div>
        @endif
    </div>
</x-client-layout>
