{{-- Requiere: $selectedOrder --}}
<x-dialog-modal wire:model="showOrderDetail" maxWidth="lg">
    <x-slot name="title">
        @if ($selectedOrder)
            <div class="flex items-start justify-between gap-4">
                <div>
                    Pedido #{{ $selectedOrder->id }}
                    <p class="mt-0.5 font-sans text-sm font-normal text-gray-500">
                        {{ $selectedOrder->business->name ?? '' }}{{ $selectedOrder->business ? ', ' : '' }}{{ $selectedOrder->created_at->translatedFormat('j \d\e F, H:i') }}
                    </p>
                </div>
                <x-admin.status-badge :status="$selectedOrder->status" class="mt-1" />
            </div>
        @endif
    </x-slot>

    <x-slot name="content">
        @if ($selectedOrder)
            <div class="space-y-6">
                {{-- Cliente --}}
                <div class="flex items-center gap-3 rounded-xl bg-gray-50 p-4">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white font-semibold text-gray-700 ring-1 ring-gray-200">
                        {{ mb_strtoupper(mb_substr($selectedOrder->user->name ?? '?', 0, 1)) }}
                    </span>
                    <div class="min-w-0">
                        <p class="font-medium text-gray-900">{{ $selectedOrder->user->name ?? 'Usuario eliminado' }}</p>
                        <p class="truncate text-gray-500">
                            {{ $selectedOrder->user->email ?? '' }}
                            @if ($selectedOrder->user?->phone)
                                <span class="mx-1 text-gray-300">|</span>{{ $selectedOrder->user->phone }}
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Productos --}}
                <div>
                    <h4 class="font-medium text-gray-900">Productos</h4>
                    <ul class="mt-2 divide-y divide-gray-100 border-y border-gray-100">
                        @foreach ($selectedOrder->products as $product)
                            <li class="flex items-center justify-between gap-4 py-2.5">
                                <span class="text-gray-700">
                                    <span class="font-semibold text-gray-900 tabular-nums">{{ $product->pivot->quantity }}×</span>
                                    {{ $product->name }}
                                    <span class="text-gray-400">a ${{ number_format($product->pivot->price_unit, 2) }}</span>
                                </span>
                                <span class="font-medium text-gray-900 tabular-nums">${{ number_format($product->pivot->subtotal, 2) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 flex items-baseline justify-between">
                        <span class="text-gray-600">Total</span>
                        <span class="font-display text-2xl font-bold text-gray-900 tabular-nums">${{ number_format($selectedOrder->total_price, 2) }}</span>
                    </div>
                </div>

                {{-- Cambiar estado --}}
                <div x-data="{ confirmCancel: false }">
                    @error('status')
                        <p class="mb-3 rounded-xl bg-red-50 p-3 text-red-700">{{ $message }}</p>
                    @enderror
                    @if ($selectedOrder->status !== 'cancelled')
                        <h4 class="font-medium text-gray-900">Estado del pedido</h4>
                        <div class="mt-2 grid grid-cols-3 gap-2" x-show="!confirmCancel">
                            @foreach (['pending' => 'Pendiente', 'completed' => 'Completado'] as $sv => $label)
                                @php $current = $selectedOrder->status === $sv; @endphp
                                <button type="button" wire:click="updateStatus({{ $selectedOrder->id }}, '{{ $sv }}')"
                                    wire:loading.attr="disabled" @disabled($current) aria-pressed="{{ $current ? 'true' : 'false' }}"
                                    @class([
                                        'rounded-full border px-3 py-2 font-semibold transition-colors',
                                        'border-green-600 bg-green-600 text-white cursor-default' => $current,
                                        'border-gray-200 text-gray-900 hover:border-gray-300 hover:bg-gray-50' => !$current,
                                    ])>
                                    {{ $label }}
                                </button>
                            @endforeach
                            <button type="button" @click="confirmCancel = true"
                                class="rounded-full border border-gray-200 px-3 py-2 font-semibold text-red-600 hover:border-red-200 hover:bg-red-50 transition-colors">
                                Cancelar
                            </button>
                        </div>

                        <div x-show="confirmCancel" x-cloak class="mt-2 rounded-xl border border-red-200 bg-red-50 p-4">
                            <p class="flex items-start gap-2 text-red-800">
                                <x-icon name="warning" class="size-5 shrink-0" />
                                Al cancelar, el stock de estos productos vuelve a estar disponible. No se puede deshacer.
                            </p>
                            <div class="mt-3 flex gap-2">
                                <x-danger-button wire:click="updateStatus({{ $selectedOrder->id }}, 'cancelled')" @click="confirmCancel = false" class="flex-1">
                                    Cancelar pedido
                                </x-danger-button>
                                <x-secondary-button @click="confirmCancel = false" class="flex-1">
                                    Mantener pedido
                                </x-secondary-button>
                            </div>
                        </div>
                    @else
                        <p class="flex items-center gap-2 rounded-xl bg-gray-50 p-4 text-gray-600">
                            <x-icon name="x" class="size-5 shrink-0 text-gray-400" />
                            Este pedido fue cancelado y ya no puede cambiar de estado.
                        </p>
                    @endif
                </div>
            </div>
        @endif
    </x-slot>

    <x-slot name="footer">
        <x-secondary-button wire:click="closeDetail">Cerrar</x-secondary-button>
    </x-slot>
</x-dialog-modal>
