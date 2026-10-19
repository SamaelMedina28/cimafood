{{-- Requiere: $orders, $showBusiness --}}
<div class="overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="border-b border-gray-100 text-left text-gray-500">
            <tr>
                <th scope="col" class="px-5 sm:px-6 py-3 font-medium">Pedido</th>
                <th scope="col" class="px-3 py-3 font-medium">Cliente</th>
                @if ($showBusiness)
                    <th scope="col" class="px-3 py-3 font-medium hidden md:table-cell">Negocio</th>
                @endif
                <th scope="col" class="px-3 py-3 font-medium">Estado</th>
                <th scope="col" class="px-3 py-3 font-medium text-right">Total</th>
                <th scope="col" class="px-5 sm:px-6 py-3"><span class="sr-only">Acciones</span></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @foreach ($orders as $order)
                <tr wire:key="order-{{ $order->id }}" @class(['hover:bg-gray-50/70', 'bg-amber-50/40' => $order->status === 'pending'])>
                    <td class="px-5 sm:px-6 py-3.5 whitespace-nowrap">
                        <p class="font-semibold text-gray-900 tabular-nums">#{{ $order->id }}</p>
                        <p class="text-xs text-gray-500">{{ $order->created_at->translatedFormat('j M, H:i') }}</p>
                    </td>
                    <td class="px-3 py-3.5">
                        <p class="font-medium text-gray-900 truncate max-w-[12rem]">{{ $order->user->name ?? 'Usuario eliminado' }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $order->products->sum('pivot.quantity') }} {{ $order->products->sum('pivot.quantity') === 1 ? 'producto' : 'productos' }}
                        </p>
                    </td>
                    @if ($showBusiness)
                        <td class="px-3 py-3.5 text-gray-600 hidden md:table-cell">{{ $order->business->name ?? '' }}</td>
                    @endif
                    <td class="px-3 py-3.5"><x-admin.status-badge :status="$order->status" /></td>
                    <td class="px-3 py-3.5 text-right font-semibold text-gray-900 tabular-nums whitespace-nowrap">
                        ${{ number_format($order->total_price, 2) }}</td>
                    <td class="px-5 sm:px-6 py-3.5 text-right">
                        <button type="button" wire:click="viewOrder({{ $order->id }})"
                            class="rounded-full border border-gray-200 px-3.5 py-1.5 text-sm font-semibold text-gray-900 hover:border-gray-300 hover:bg-white transition-colors">
                            Ver
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
