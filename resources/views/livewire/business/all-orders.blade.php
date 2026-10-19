<div class="space-y-6">
    @include('livewire.business.partials.order-stats')

    <section class="rounded-2xl border border-gray-200 overflow-hidden">
        {{-- Filtros --}}
        <div class="space-y-3 border-b border-gray-100 p-4 sm:p-5">
            <div class="flex flex-col gap-3 sm:flex-row">
            <div class="relative flex-1">
                <label for="order-search" class="sr-only">Buscar por cliente</label>
                <x-icon name="search" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 size-4 text-gray-400" />
                <x-input id="order-search" type="search" wire:model.live.debounce.400ms="search"
                    placeholder="Buscar por nombre o correo del cliente" class="w-full pl-9 text-sm" />
            </div>

            @if ($businesses->count() > 1)
                <label for="order-business" class="sr-only">Negocio</label>
                <select id="order-business" wire:model.live="businessFilter"
                    class="w-full sm:w-56 rounded-lg border-gray-200 text-sm shadow-sm focus:border-green-600 focus:ring-green-600">
                    <option value="all">Todos los negocios</option>
                    @foreach ($businesses as $biz)
                        <option value="{{ $biz->id }}">{{ $biz->name }}</option>
                    @endforeach
                </select>
            @endif
            </div>

            @include('livewire.business.partials.status-filter')
        </div>

        <div wire:loading.class="opacity-60" wire:target="search, businessFilter, statusFilter, gotoPage, nextPage, previousPage">
            @if ($orders->count() === 0)
                <x-admin.empty icon="receipt" :title="$search !== '' || $statusFilter !== 'all' || $businessFilter !== 'all' ? 'Ningún pedido coincide' : 'Aún no hay pedidos'">
                    @if ($search !== '' || $statusFilter !== 'all' || $businessFilter !== 'all')
                        <button type="button" wire:click="$set('statusFilter','all'); $set('businessFilter','all'); $set('search','')"
                            class="mt-3 font-semibold text-green-700 hover:text-green-800">Quitar filtros</button>
                    @else
                        Cuando un cliente haga un pedido en tus negocios aparecerá aquí.
                    @endif
                </x-admin.empty>
            @else
                @include('livewire.business.partials.order-table', ['showBusiness' => $businesses->count() > 1])
            @endif
        </div>
    </section>

    @if ($orders->hasPages())
        {{ $orders->links() }}
    @endif

    @include('livewire.business.partials.order-detail')
</div>
