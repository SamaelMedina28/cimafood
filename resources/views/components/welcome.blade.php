 @props([
    'totalSales' => 0,
    'ordersCount' => 0,
    'topProducts' => collect(),
    'topBusinesses' => collect(),
    'period' => 'month',
    'recentOrders' => collect(),
    'avgOrderValue' => 0,
    'salesByDay' => collect(),
])

<div class="bg-gray-50 min-h-screen">
    {{-- Header del Dashboard --}}
    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Panel de Control
                </h1>
            </div>

            <div class="flex items-center gap-3 bg-white p-1 rounded-lg shadow-sm border border-gray-100">
                <form action="{{ route('dashboard') }}" method="GET" id="periodForm">
                    <div class="flex p-1 bg-gray-100 rounded-lg">
                        @foreach (['today' => 'Hoy', 'week' => 'Semana', 'month' => 'Mes', 'year' => 'Año'] as $val => $label)
                            <button type="submit" name="period" value="{{ $val }}"
                                class="px-4 py-1.5 text-xs font-bold uppercase tracking-wider rounded-lg transition-all duration-200 {{ $period == $val ? 'bg-white text-green-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="p-6 lg:p-8 space-y-8">
        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Total Ventas --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Ingresos Totales</h3>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-gray-900">${{ number_format($totalSales, 2) }}</span>
                </div>
            </div>

            {{-- Pedidos --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Pedidos</h3>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-gray-900">{{ $ordersCount }}</span>
                </div>
            </div>

            {{-- Ticket Promedio --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Ticket Promedio</h3>
                <div class="flex items-baseline gap-1 mt-1">
                    <span class="text-2xl font-black text-gray-900">${{ number_format($avgOrderValue, 2) }}</span>
                </div>
            </div>

            {{-- Periodo Activo --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6 transition-all hover:shadow-md">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-orange-50 rounded-lg">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Periodo</h3>
                <div class="flex items-baseline gap-1 mt-1">
                    <span
                        class="text-2xl font-black text-gray-900 capitalize">{{ $period == 'today' ? 'Hoy' : ($period == 'week' ? 'Semanal' : ($period == 'month' ? 'Mensual' : 'Anual')) }}</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Recent Orders Table --}}
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-900">Pedidos Recientes</h3>
                    <a href="{{ route('order.index') }}"
                        class="text-sm font-bold text-green-600 hover:text-green-700">Ver todos</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-gray-400 text-xs font-bold uppercase tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Cliente</th>
                                <th class="px-6 py-4">Negocio</th>
                                <th class="px-6 py-4">Total</th>
                                <th class="px-6 py-4">Estado</th>
                                <th class="px-6 py-4">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                                {{ substr($order->user->name, 0, 1) }}
                                            </div>
                                            <span
                                                class="text-sm font-semibold text-gray-700">{{ $order->user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $order->business->name }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                        ${{ number_format($order->total_price, 2) }}</td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                            {{ $order->status == 'completed'
                                                ? 'bg-green-100 text-green-700'
                                                : ($order->status == 'pending'
                                                    ? 'bg-yellow-100 text-yellow-700'
                                                    : 'bg-red-100 text-red-700') }}">
                                            {{ $order->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-xs text-gray-400 font-medium">
                                        {{ $order->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic">No hay
                                        pedidos registrados</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Performance Column --}}
            <div class="space-y-8">
                {{-- Top Productos --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        Productos Estrella
                    </h3>
                    <div class="space-y-5">
                        @forelse ($topProducts as $product)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-lg bg-gray-50 border border-gray-100 flex items-center justify-center group-hover:bg-orange-50 transition-colors">
                                        <span
                                            class="text-xs font-black text-gray-400 group-hover:text-orange-600">{{ $loop->iteration }}</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $product->name }}</p>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                            {{ $product->total_quantity }} unidades</p>
                                    </div>
                                </div>
                                <span
                                    class="text-sm font-black text-gray-900">${{ number_format($product->total_revenue, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center">Sin datos de productos</p>
                        @endforelse
                    </div>
                </div>

                {{-- Top Negocios --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Líderes en Ventas
                    </h3>
                    <div class="space-y-5">
                        @forelse ($topBusinesses as $business)
                            <div class="flex items-center justify-between group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center overflow-hidden border-2 border-white shadow-sm">
                                        @if ($business->logo)
                                            <img src="{{ asset('storage/' . $business->logo) }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            <span
                                                class="text-xs font-bold text-blue-600">{{ substr($business->name, 0, 1) }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-bold text-gray-800">{{ $business->name }}</p>
                                </div>
                                <span
                                    class="text-sm font-black text-gray-900">${{ number_format($business->orders_sum_total_price ?? 0, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center">Sin datos de negocios</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
