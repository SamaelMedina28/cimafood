<x-app-layout>
    @php $active = $business->status === 'active'; @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-6 sm:pt-10">
        <a href="{{ route('business.index') }}" wire:navigate
            class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-900">
            <x-icon name="chevron-left" class="size-4" />
            Negocios
        </a>

        {{-- Portada --}}
        <div class="mt-3 aspect-[16/5] overflow-hidden rounded-3xl bg-green-50">
            @if ($business->banner_url)
                <img src="{{ $business->banner_url }}" alt="" class="size-full object-cover">
            @endif
        </div>

        <div class="px-1 sm:px-4">
            <div class="-mt-10 sm:-mt-12 size-20 sm:size-24 overflow-hidden rounded-full bg-white ring-4 ring-white shadow-sm">
                @if ($business->logo_url)
                    <img src="{{ $business->logo_url }}" alt="Logo de {{ $business->name }}" class="size-full object-cover">
                @else
                    <span class="flex size-full items-center justify-center bg-green-50 font-display text-3xl font-bold text-green-700">
                        {{ mb_strtoupper(mb_substr($business->name, 0, 1)) }}</span>
                @endif
            </div>

            <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">{{ $business->name }}</h1>
                        <span @class([
                            'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset',
                            'bg-green-50 text-green-800 ring-green-200' => $active,
                            'bg-gray-100 text-gray-600 ring-gray-200' => !$active,
                        ])>
                            <span @class(['size-1.5 rounded-full', 'bg-green-600' => $active, 'bg-gray-400' => !$active])></span>
                            {{ $active ? 'Visible en la tienda' : 'Oculto de la tienda' }}
                        </span>
                    </div>
                    @if ($business->description)
                        <p class="mt-2 max-w-2xl text-gray-600">{{ $business->description }}</p>
                    @endif
                    <dl class="mt-4 flex flex-wrap gap-x-8 gap-y-2 text-sm">
                        <div class="flex items-center gap-1.5">
                            <dt><x-icon name="clock" class="size-4 text-gray-400" /><span class="sr-only">Horario</span></dt>
                            <dd class="text-gray-700 tabular-nums">{{ substr($business->open_time, 0, 5) }} a {{ substr($business->close_time, 0, 5) }}</dd>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <dt class="text-gray-500">Teléfono</dt>
                            <dd class="text-gray-700">{{ $business->phone ?: 'Sin registrar' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex shrink-0 gap-2">
                    @if ($active)
                        <a href="{{ route('store.business', $business) }}" wire:navigate
                            class="inline-flex items-center rounded-full border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-900 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                            Ver en la tienda
                        </a>
                    @endif
                    <a href="{{ route('business.edit', $business) }}" wire:navigate
                        class="inline-flex items-center gap-1.5 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                        <x-icon name="pencil" class="size-4" />
                        Editar
                    </a>
                </div>
            </div>
        </div>

        <section class="mt-12" aria-labelledby="business-orders-title">
            <h2 id="business-orders-title" class="mb-4 font-display text-2xl font-bold text-gray-900">Pedidos</h2>
            @livewire('business.orders', ['business' => $business])
        </section>
    </div>
</x-app-layout>
