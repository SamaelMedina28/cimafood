<x-app-layout>
    @php
        $periods = ['today' => 'Hoy', 'week' => 'Semana', 'month' => 'Mes', 'year' => 'Año'];
        $periodLabel = ['today' => 'Hoy', 'week' => 'Esta semana', 'month' => 'Este mes', 'year' => 'Este año'][$period] ?? 'Periodo seleccionado';

        $chartMax = max(array_column($salesSeries, 'total') ?: [0]);
        // Tope del eje redondeado a un número limpio
        $magnitude = $chartMax > 0 ? 10 ** floor(log10($chartMax)) : 1;
        $axisMax = $chartMax > 0 ? ceil($chartMax / $magnitude) * $magnitude : 100;
        $ticks = [$axisMax, $axisMax / 2, 0];
        $labelEvery = max(1, (int) ceil(count($salesSeries) / 8));
        $money = fn($v) => '$' . ($v >= 1000 ? rtrim(rtrim(number_format($v / 1000, 1), '0'), '.') . 'K' : number_format($v, 0));
    @endphp

    <x-slot name="header">
        <x-admin.header title="Resumen" description="Cómo van las ventas de tus negocios.">
            <x-slot name="actions">
                <form action="{{ route('dashboard') }}" method="GET"
                    class="inline-flex rounded-full border border-gray-200 bg-white p-1" role="group" aria-label="Periodo">
                    @foreach ($periods as $val => $label)
                        <button type="submit" name="period" value="{{ $val }}"
                            aria-pressed="{{ $period == $val ? 'true' : 'false' }}"
                            @class([
                                'rounded-full px-3.5 py-1.5 text-sm font-medium transition-colors whitespace-nowrap',
                                'bg-green-600 text-white' => $period == $val,
                                'text-gray-600 hover:text-gray-900' => $period != $val,
                            ])>
                            {{ $label }}
                        </button>
                    @endforeach
                </form>
            </x-slot>
        </x-admin.header>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-8 space-y-8">

        {{-- Métricas --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <x-admin.stat label="Ingresos" :value="'$' . number_format($totalSales, 2)" :hint="$periodLabel . ', sin cancelados'" class="col-span-2 lg:col-span-1" />
            <x-admin.stat label="Pedidos" :value="number_format($ordersCount)" :hint="$periodLabel" />
            <x-admin.stat label="Ticket promedio" :value="'$' . number_format($avgOrderValue, 2)" hint="Por pedido" />
            <a href="{{ route('order.index', ['statusFilter' => 'pending']) }}" wire:navigate
                class="col-span-2 lg:col-span-1 block rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600">
                <x-admin.stat label="Por atender" :value="number_format($pendingCount)"
                    :hint="$pendingCount > 0 ? 'Pedidos pendientes, ver ahora' : 'Sin pedidos pendientes'"
                    :highlight="$pendingCount > 0" class="h-full" />
            </a>
        </div>

        {{-- Gráfica de ventas --}}
        <section class="rounded-2xl border border-gray-200 p-5 sm:p-6" aria-labelledby="sales-chart-title">
            <div class="flex flex-wrap items-baseline justify-between gap-2">
                <h2 id="sales-chart-title" class="font-display text-xl font-bold text-gray-900">
                    Ventas {{ $period === 'today' ? 'por hora' : ($period === 'year' ? 'por mes' : 'por día') }}
                </h2>
                <p class="text-sm text-gray-500">{{ $periodLabel }}</p>
            </div>

            @if ($chartMax > 0)
                <div class="mt-6 flex gap-3">
                    {{-- Eje Y --}}
                    <div class="flex h-56 flex-col justify-between text-right text-xs text-gray-500 tabular-nums" aria-hidden="true">
                        @foreach ($ticks as $tick)
                            <span class="-translate-y-1/2 first:translate-y-0 last:translate-y-0">{{ $money($tick) }}</span>
                        @endforeach
                    </div>

                    <div class="relative flex-1 min-w-0">
                        {{-- Rejilla --}}
                        <div class="absolute inset-x-0 top-0 h-56 flex flex-col justify-between pointer-events-none" aria-hidden="true">
                            @foreach ($ticks as $tick)
                                <div class="border-t border-gray-100"></div>
                            @endforeach
                        </div>

                        {{-- Columnas --}}
                        <ol class="relative flex h-56 items-end gap-0.5" role="list" aria-label="Ventas {{ mb_strtolower($periodLabel) }}">
                            @foreach ($salesSeries as $point)
                                <li class="group relative flex h-full flex-1 items-end justify-center focus:outline-none"
                                    tabindex="0" aria-label="{{ $point['label'] }}: ${{ number_format($point['total'], 2) }}">
                                    <span class="absolute inset-0 rounded-md group-hover:bg-gray-50 group-focus-visible:bg-gray-50"></span>
                                    <span class="relative w-full max-w-6 rounded-t bg-green-600 group-hover:bg-green-700 group-focus-visible:bg-green-700"
                                        style="height: {{ $point['total'] > 0 ? max(2, $point['total'] / $axisMax * 100) : 0 }}%"></span>
                                    {{-- Tooltip --}}
                                    <span class="pointer-events-none absolute bottom-full left-1/2 z-10 mb-2 hidden -translate-x-1/2 whitespace-nowrap rounded-lg bg-gray-900 px-2.5 py-1.5 text-xs text-white shadow-lg group-hover:block group-focus-visible:block">
                                        <span class="block text-gray-300">{{ $point['label'] }}</span>
                                        <span class="block font-semibold tabular-nums">${{ number_format($point['total'], 2) }}</span>
                                    </span>
                                </li>
                            @endforeach
                        </ol>

                        {{-- Eje X --}}
                        <div class="mt-2 flex gap-0.5 text-xs text-gray-500" aria-hidden="true">
                            @foreach ($salesSeries as $i => $point)
                                <span class="relative h-4 flex-1 min-w-0">
                                    @if ($i % $labelEvery === 0)
                                        {{-- En móvil solo se muestra una de cada dos etiquetas --}}
                                        <span @class([
                                            'absolute left-1/2 -translate-x-1/2 whitespace-nowrap',
                                            'hidden sm:inline' => intdiv($i, $labelEvery) % 2 === 1,
                                        ])>{{ $point['label'] }}</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <details class="mt-4 text-sm">
                    <summary class="cursor-pointer text-gray-500 hover:text-gray-900">Ver datos en tabla</summary>
                    <table class="mt-3 w-full max-w-sm text-left">
                        <thead class="text-gray-500">
                            <tr><th class="py-1 font-medium">{{ $period === 'today' ? 'Hora' : ($period === 'year' ? 'Mes' : 'Día') }}</th><th class="py-1 text-right font-medium">Ventas</th></tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach ($salesSeries as $point)
                                <tr><td class="py-1">{{ $point['label'] }}</td><td class="py-1 text-right tabular-nums">${{ number_format($point['total'], 2) }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </details>
            @else
                <x-admin.empty icon="receipt" title="Sin ventas en este periodo" class="!py-12">
                    Cuando entren pedidos aparecerán aquí. Prueba con un periodo más amplio.
                </x-admin.empty>
            @endif
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Pedidos recientes --}}
            <section class="lg:col-span-2 rounded-2xl border border-gray-200 overflow-hidden" aria-labelledby="recent-title">
                <div class="flex items-center justify-between px-5 sm:px-6 py-4 border-b border-gray-100">
                    <h2 id="recent-title" class="font-display text-xl font-bold text-gray-900">Pedidos recientes</h2>
                    <a href="{{ route('order.index') }}" wire:navigate class="text-sm font-semibold text-green-700 hover:text-green-800">Ver todos</a>
                </div>
                @if ($recentOrders->count() > 0)
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentOrders as $order)
                            <li class="flex items-center gap-4 px-5 sm:px-6 py-3.5">
                                <span class="flex size-9 shrink-0 items-center justify-center rounded-full bg-gray-100 text-sm font-semibold text-gray-700">
                                    {{ mb_strtoupper(mb_substr($order->user->name ?? '?', 0, 1)) }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-medium text-gray-900">{{ $order->user->name ?? 'Usuario eliminado' }}</p>
                                    <p class="truncate text-xs text-gray-500">#{{ $order->id }}, {{ $order->business->name }}, {{ $order->created_at->diffForHumans() }}</p>
                                </div>
                                <x-admin.status-badge :status="$order->status" class="hidden sm:inline-flex" />
                                <span class="w-20 text-right text-sm font-semibold text-gray-900 tabular-nums">${{ number_format($order->total_price, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <x-admin.empty icon="receipt" title="Aún no hay pedidos">
                        Los pedidos que hagan los clientes aparecerán aquí.
                    </x-admin.empty>
                @endif
            </section>

            <div class="space-y-8">
                {{-- Productos más vendidos --}}
                <section class="rounded-2xl border border-gray-200 p-5 sm:p-6" aria-labelledby="top-products-title">
                    <h2 id="top-products-title" class="font-display text-xl font-bold text-gray-900">Más vendidos</h2>
                    <p class="text-sm text-gray-500">{{ $periodLabel }}</p>
                    @php $topMax = $topProducts->max('total_quantity') ?: 1; @endphp
                    <ol class="mt-5 space-y-4">
                        @forelse ($topProducts as $product)
                            <li>
                                <div class="flex items-baseline justify-between gap-3 text-sm">
                                    <span class="truncate font-medium text-gray-900">{{ $product->name }}</span>
                                    <span class="shrink-0 text-gray-500 tabular-nums">{{ $product->total_quantity }} vendidos</span>
                                </div>
                                <div class="mt-1.5 h-1.5 rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-green-600" style="width: {{ $product->total_quantity / $topMax * 100 }}%"></div>
                                </div>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">Sin ventas en este periodo.</li>
                        @endforelse
                    </ol>
                </section>

                {{-- Ventas por negocio --}}
                @if ($topBusinesses->count() > 1)
                    <section class="rounded-2xl border border-gray-200 p-5 sm:p-6" aria-labelledby="top-biz-title">
                        <h2 id="top-biz-title" class="font-display text-xl font-bold text-gray-900">Por negocio</h2>
                        <p class="text-sm text-gray-500">{{ $periodLabel }}</p>
                        <ul class="mt-5 space-y-4">
                            @foreach ($topBusinesses as $business)
                                <li class="flex items-center gap-3">
                                    <span class="size-9 shrink-0 overflow-hidden rounded-full bg-green-50 ring-1 ring-gray-200">
                                        @if ($business->logo_url)
                                            <img src="{{ $business->logo_url }}" alt="" class="size-full object-cover">
                                        @endif
                                    </span>
                                    <span class="min-w-0 flex-1 truncate text-sm font-medium text-gray-900">{{ $business->name }}</span>
                                    <span class="text-sm font-semibold text-gray-900 tabular-nums">${{ number_format($business->orders_sum_total_price ?? 0, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
