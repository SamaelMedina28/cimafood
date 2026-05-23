<div class="space-y-6">

    {{-- Stats Grid (Subtle and clean, matching all-orders) --}}
    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow-sm">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Pedidos</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow-sm">
            <p class="text-xs font-semibold text-yellow-500 uppercase tracking-wider">Pendientes</p>
            <p class="text-2xl font-bold text-yellow-500 mt-1">{{ $stats['pending'] }}</p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow-sm">
            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">Completados</p>
            <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['completed'] }}</p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow-sm">
            <p class="text-xs font-semibold text-red-500 uppercase tracking-wider">Cancelados</p>
            <p class="text-2xl font-bold text-red-500 mt-1">{{ $stats['cancelled'] }}</p>
        </div>
        <div class="p-4 bg-white border border-gray-200 rounded-md shadow-sm col-span-2 sm:col-span-1">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Ingresos</p>
            <p class="text-2xl font-bold text-gray-950 mt-1">${{ number_format($stats['revenue'], 2) }}</p>
        </div>
    </div>

    {{-- Filtro por estado --}}
    <div class="flex flex-wrap gap-1.5">
        @foreach ([
        'all' => ['label' => 'Todos', 'active' => 'bg-gray-800 text-white border-gray-800 hover:bg-gray-700', 'inactive' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'],
        'pending' => ['label' => 'Pendientes', 'active' => 'bg-yellow-500 text-white border-yellow-500 hover:bg-amber-500', 'inactive' => 'bg-white text-yellow-750 border-yellow-300 hover:bg-yellow-50'],
        'completed' => ['label' => 'Completados', 'active' => 'bg-emerald-700 text-white border-emerald-700 hover:bg-emerald-800', 'inactive' => 'bg-white text-emerald-750 border-emerald-300 hover:bg-emerald-50'],
        'cancelled' => ['label' => 'Cancelados', 'active' => 'bg-red-500 text-white border-red-500 hover:bg-red-600', 'inactive' => 'bg-white text-red-750 border-red-300 hover:bg-red-50'],
    ] as $val => $cfg)
            <button wire:click="$set('statusFilter', '{{ $val }}')"
                class="inline-flex items-center px-3.5 py-1.5 border rounded-md text-xs font-semibold shadow-sm transition ease-in-out duration-150 {{ $statusFilter === $val ? $cfg['active'] : $cfg['inactive'] }}">
                {{ $cfg['label'] }}
                @if ($val !== 'all')
                    <span class="text-2xs ml-1 {{ $statusFilter === $val ? 'opacity-85' : 'opacity-60' }}">
                        ({{ $stats[$val] }})
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    {{-- ─── Table / Lista de Pedidos ─── --}}
    <div class="bg-white rounded-md border border-gray-200 overflow-hidden shadow-sm">
        @if ($orders->count() === 0)
            <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
                <div
                    class="w-16 h-16 bg-gray-50 rounded-lg flex items-center justify-center mb-4 border border-gray-100">
                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-base font-semibold text-gray-900">No hay pedidos</h3>
                <p class="mt-1.5 text-sm text-gray-500 max-w-xs">
                    {{ $statusFilter === 'all' ? 'Este negocio aún no ha recibido pedidos.' : 'No hay pedidos con el estado seleccionado.' }}
                </p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50/70">
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                #</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Cliente</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                Productos</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Estado</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider hidden md:table-cell">
                                Fecha</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-150 bg-white">
                        @foreach ($orders as $order)
                            @php
                                $statusConfig = [
                                    'pending' => [
                                        'label' => 'Pendiente',
                                        'class' => 'bg-amber-50 text-amber-700 border-amber-200/60',
                                        'dot' => 'bg-amber-500',
                                    ],
                                    'completed' => [
                                        'label' => 'Completado',
                                        'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
                                        'dot' => 'bg-emerald-500',
                                    ],
                                    'cancelled' => [
                                        'label' => 'Cancelado',
                                        'class' => 'bg-red-50 text-red-650 border-red-200/60',
                                        'dot' => 'bg-red-500',
                                    ],
                                ];
                                $cfg = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors duration-150">
                                <td class="px-6 py-4 text-xs font-mono text-gray-400">#{{ $order->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 shadow-sm border border-gray-300">
                                            <span
                                                class="text-xs font-bold text-gray-600">{{ strtoupper(substr($order->user->name ?? 'U', 0, 1)) }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 truncate">
                                                {{ $order->user->name ?? 'Usuario' }}</p>
                                            <p class="text-xs text-gray-500 truncate hidden sm:block">
                                                {{ $order->user->email ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 hidden sm:table-cell">
                                    <span class="text-xs text-gray-500">
                                        {{ $order->products->count() }}
                                        {{ $order->products->count() === 1 ? 'producto' : 'productos' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="text-sm font-bold text-gray-950">${{ number_format($order->total_price, 2) }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $cfg['class'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $cfg['dot'] }}"></span>
                                        {{ $cfg['label'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 hidden md:table-cell">
                                    <p class="text-xs font-medium text-gray-700">
                                        {{ $order->created_at->format('d/m/Y') }}</p>
                                    <p class="text-2xs text-gray-400">{{ $order->created_at->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="viewOrder({{ $order->id }})"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md text-xs font-semibold text-gray-750 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-1 shadow-sm transition">
                                        Ver
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Paginación --}}
    @if ($orders->hasPages())
        <div class="mt-2">
            {{ $orders->links() }}
        </div>
    @endif

    {{-- ─── Modal Detalle ─── --}}
    <x-dialog-modal wire:model="showOrderDetail" maxWidth="lg">
        <x-slot name="title">
            <div class="border-b border-gray-200 pb-3">
                <h3 class="text-lg font-bold text-gray-900">Detalles del Pedido #{{ $selectedOrder?->id }}</h3>
            </div>
        </x-slot>

        <x-slot name="content">
            @if ($selectedOrder)
                <div class="space-y-5">
                    {{-- Cliente --}}
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-md">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Información del
                            Cliente</h4>
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0 border border-gray-300 shadow-sm">
                                <span
                                    class="text-xs font-bold text-gray-600">{{ strtoupper(substr($selectedOrder->user->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $selectedOrder->user->name ?? 'Usuario eliminado' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $selectedOrder->user->email ?? '—' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $selectedOrder->user->phone ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between text-xs text-gray-500">
                            <div>
                                <span class="font-semibold text-gray-700">Fecha:</span>
                                {{ $selectedOrder->created_at->format('d/m/Y h:i A') }}
                            </div>
                        </div>
                    </div>

                    {{-- Cambiar estado --}}
                    <div x-data="{ confirmCancel: false }">
                        @if ($selectedOrder->status !== 'cancelled')
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cambiar Estado
                            </h4>
                            <div class="flex gap-2">
                                @foreach ([
        'pending' => ['label' => 'Pendiente', 'active' => 'bg-yellow-500 text-white border-yellow-500 hover:bg-amber-500', 'inactive' => 'bg-white text-yellow-700 border-yellow-400 hover:bg-yellow-50'],
        'completed' => ['label' => 'Completado', 'active' => 'bg-emerald-700 text-white border-emerald-700 hover:bg-emerald-850', 'inactive' => 'bg-white text-emerald-750 border-emerald-300 hover:bg-emerald-50'],
        'cancelled' => ['label' => 'Cancelado', 'active' => 'bg-red-600 text-white border-red-600 hover:bg-red-700', 'inactive' => 'bg-white text-red-700 border-red-400 hover:bg-red-50'],
    ] as $sv => $sc)
                                    @if ($sv === 'cancelled')
                                        <button @click="confirmCancel = true" wire:loading.attr="disabled"
                                            class="flex-1 py-1.5 px-3 border rounded-md text-xs font-semibold shadow-sm transition-all duration-150 {{ $selectedOrder->status === $sv ? $sc['active'] : $sc['inactive'] }}">
                                            {{ $sc['label'] }}
                                        </button>
                                    @else
                                        <button
                                            wire:click="updateStatus({{ $selectedOrder->id }}, '{{ $sv }}')"
                                            wire:loading.attr="disabled"
                                            class="flex-1 py-1.5 px-3 border rounded-md text-xs font-semibold shadow-sm transition-all duration-150 {{ $selectedOrder->status === $sv ? $sc['active'] : $sc['inactive'] }}">
                                            {{ $sc['label'] }}
                                        </button>
                                    @endif
                                @endforeach
                            </div>

                            {{-- Modal confirm cancelación --}}
                            <div x-show="confirmCancel" x-transition
                                class="mt-3 p-3 border border-red-200 bg-red-50 rounded-lg">
                                <p class="text-xs text-red-700 font-medium mb-3">⚠️ ¿Seguro que deseas cancelar este
                                    pedido? Esta acción restaurará el stock pero no se puede deshacer.</p>
                                <div class="flex gap-2">
                                    <button wire:click="updateStatus({{ $selectedOrder->id }}, 'cancelled')"
                                        @click="confirmCancel = false"
                                        class="flex-1 py-1.5 px-3 bg-red-600 text-white border border-red-600 rounded-md text-xs font-semibold hover:bg-red-700 transition-all">
                                        Sí, cancelar
                                    </button>
                                    <button @click="confirmCancel = false"
                                        class="flex-1 py-1.5 px-3 bg-white text-gray-600 border border-gray-300 rounded-md text-xs font-semibold hover:bg-gray-50 transition-all">
                                        No, volver
                                    </button>
                                </div>
                            </div>
                        @else
                            {{-- Pedido cancelado --}}
                            <div class="flex items-center gap-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <span class="text-red-500 text-lg">✕</span>
                                <p class="text-xs font-semibold text-red-700">Este pedido fue cancelado y no puede
                                    modificarse.</p>
                            </div>
                        @endif
                    </div>

                    {{-- Productos --}}
                    <div>
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Productos del Pedido
                        </h4>
                        <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                            @foreach ($selectedOrder->products as $product)
                                <div
                                    class="flex items-center justify-between py-2 px-3 bg-gray-50 border border-gray-200 rounded-md">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-gray-900 truncate">{{ $product->name }}
                                        </p>
                                        <p class="text-2xs text-gray-500">
                                            x{{ $product->pivot->quantity }} ·
                                            ${{ number_format($product->pivot->price_unit, 2) }} c/u
                                        </p>
                                    </div>
                                    <p class="text-xs font-bold text-gray-900 flex-shrink-0 ml-3">
                                        ${{ number_format($product->pivot->subtotal, 2) }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total</span>
                        <span
                            class="text-lg font-bold text-gray-955">${{ number_format($selectedOrder->total_price, 2) }}</span>
                    </div>
                </div>
            @endif
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="closeDetail" class="!rounded-md">
                Cerrar
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

</div>
