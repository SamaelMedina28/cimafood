<div class="space-y-6">
    @include('livewire.business.partials.order-stats')

    <section class="rounded-2xl border border-gray-200 overflow-hidden">
        <div class="border-b border-gray-100 p-4 sm:p-5">
            @include('livewire.business.partials.status-filter')
        </div>

        <div wire:loading.class="opacity-60" wire:target="statusFilter, gotoPage, nextPage, previousPage">
            @if ($orders->count() === 0)
                <x-admin.empty icon="receipt" :title="$statusFilter === 'all' ? 'Aún no hay pedidos' : 'No hay pedidos con este estado'">
                    {{ $statusFilter === 'all' ? 'Cuando un cliente pida en este negocio aparecerá aquí.' : '' }}
                </x-admin.empty>
            @else
                @include('livewire.business.partials.order-table', ['showBusiness' => false])
            @endif
        </div>
    </section>

    @if ($orders->hasPages())
        {{ $orders->links() }}
    @endif

    @include('livewire.business.partials.order-detail')
</div>
