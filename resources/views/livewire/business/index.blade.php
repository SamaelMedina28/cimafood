<div>
    @if ($businesses->count() === 0)
        <div class="rounded-2xl border border-dashed border-gray-300">
            <x-admin.empty icon="store" title="Aún no tienes negocios">
                <div class="flex flex-col items-center justify-center">
                    <p class="text-sm text-gray-700">Registra tu primer negocio para empezar a publicar productos y recibir pedidos.</p>
                    <a href="{{ route('business.create') }}" wire:navigate
                        class="mt-5 inline-flex items-center gap-1.5 rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                        <x-icon name="plus" class="size-4" />
                        Registrar negocio
                    </a>
                </div>
            </x-admin.empty>
        </div>
    @else
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($businesses as $business)
                @php $active = $business->status === 'active'; @endphp
                <li wire:key="business-{{ $business->id }}" class="overflow-hidden rounded-2xl border border-gray-200">
                    <a href="{{ route('business.show', $business) }}" wire:navigate class="group block">
                        <div class="relative aspect-[16/6] bg-green-50">
                            @if ($business->banner_url)
                                <img src="{{ $business->banner_url }}" alt="" @class(['size-full object-cover', 'grayscale opacity-70' => !$active])>
                            @endif
                        </div>
                        <div class="flex items-center gap-3 px-5 pt-4">
                            <span class="-mt-10 size-14 shrink-0 overflow-hidden rounded-full bg-white ring-4 ring-white shadow-sm">
                                @if ($business->logo_url)
                                    <img src="{{ $business->logo_url }}" alt="" class="size-full object-cover">
                                @else
                                    <span class="flex size-full items-center justify-center bg-green-50 font-display text-xl font-bold text-green-700">
                                        {{ mb_strtoupper(mb_substr($business->name, 0, 1)) }}</span>
                                @endif
                            </span>
                            <h2 class="min-w-0 flex-1 truncate font-display text-lg font-bold text-gray-900 group-hover:text-green-700 transition-colors">
                                {{ $business->name }}</h2>
                            <span @class([
                                'inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset',
                                'bg-green-50 text-green-800 ring-green-200' => $active,
                                'bg-gray-100 text-gray-600 ring-gray-200' => !$active,
                            ])>
                                <span @class(['size-1.5 rounded-full', 'bg-green-600' => $active, 'bg-gray-400' => !$active])></span>
                                {{ $active ? 'Visible' : 'Oculto' }}
                            </span>
                        </div>
                    </a>

                    <dl class="mt-3 grid grid-cols-2 gap-4 px-5 text-sm">
                        <div>
                            <dt class="text-gray-500">Horario</dt>
                            <dd class="font-medium text-gray-900 tabular-nums">{{ substr($business->open_time, 0, 5) }} a {{ substr($business->close_time, 0, 5) }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Teléfono</dt>
                            <dd class="font-medium text-gray-900 truncate">{{ $business->phone ?: 'Sin registrar' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-4 flex items-center gap-2 border-t border-gray-100 px-5 py-3">
                        <a href="{{ route('business.show', $business) }}" wire:navigate
                            class="rounded-full px-3 py-1.5 text-sm font-semibold text-gray-900 hover:bg-gray-100">Ver pedidos</a>
                        <a href="{{ route('business.edit', $business) }}" wire:navigate
                            class="rounded-full px-3 py-1.5 text-sm font-semibold text-gray-900 hover:bg-gray-100">Editar</a>
                        <button type="button" wire:click="openModal({{ $business->id }})"
                            class="ml-auto rounded-full px-3 py-1.5 text-sm font-semibold text-red-600 hover:bg-red-50">Eliminar</button>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <x-dialog-modal wire:model="isOpen" maxWidth="md">
        <x-slot name="title">¿Eliminar este negocio?</x-slot>
        <x-slot name="content">
            Se borrarán también sus productos, pedidos y reseñas. Esta acción no se puede deshacer.
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('isOpen', false)">Conservar</x-secondary-button>
            <x-danger-button wire:click="delete">Eliminar negocio</x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>
