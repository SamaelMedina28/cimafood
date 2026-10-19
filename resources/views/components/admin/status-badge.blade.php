@props(['status'])

@php
    $config = [
        'pending' => ['label' => 'Pendiente', 'class' => 'bg-amber-50 text-amber-800 ring-amber-200', 'dot' => 'bg-amber-500'],
        'completed' => ['label' => 'Completado', 'class' => 'bg-green-50 text-green-800 ring-green-200', 'dot' => 'bg-green-600'],
        'cancelled' => ['label' => 'Cancelado', 'class' => 'bg-gray-100 text-gray-600 ring-gray-200', 'dot' => 'bg-gray-400'],
    ][$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-600 ring-gray-200', 'dot' => 'bg-gray-400'];
@endphp

<span {{ $attributes->class(['inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset whitespace-nowrap', $config['class']]) }}>
    <span class="size-1.5 rounded-full {{ $config['dot'] }}"></span>
    {{ $config['label'] }}
</span>
