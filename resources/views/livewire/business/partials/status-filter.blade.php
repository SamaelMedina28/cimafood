<div class="flex gap-2 overflow-x-auto scrollbar-none" role="group" aria-label="Filtrar por estado">
    @foreach (['all' => 'Todos', 'pending' => 'Pendientes', 'completed' => 'Completados', 'cancelled' => 'Cancelados'] as $val => $label)
        @php $active = $statusFilter === $val; @endphp
        <button type="button" wire:click="$set('statusFilter', '{{ $val }}')" aria-pressed="{{ $active ? 'true' : 'false' }}"
            @class([
                'inline-flex shrink-0 items-center gap-1.5 rounded-full border px-3.5 py-1.5 text-sm font-medium whitespace-nowrap transition-colors',
                'border-green-600 bg-green-600 text-white' => $active,
                'border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:text-gray-900' => !$active,
            ])>
            {{ $label }}
            <span @class(['tabular-nums', 'text-white/80' => $active, 'text-gray-400' => !$active])>
                {{ $val === 'all' ? $stats['total'] : $stats[$val] }}
            </span>
        </button>
    @endforeach
</div>
