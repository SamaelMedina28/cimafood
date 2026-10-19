@props(['label', 'value', 'hint' => null, 'highlight' => false])

<div {{ $attributes->class(['rounded-2xl border p-5', 'border-green-200 bg-green-50' => $highlight, 'border-gray-200 bg-white' => !$highlight]) }}>
    <p class="text-sm font-medium text-gray-600">{{ $label }}</p>
    <p class="mt-2 font-display text-3xl font-bold tracking-tight text-gray-900 tabular-nums">{{ $value }}</p>
    @if ($hint)
        <p class="mt-1 text-xs text-gray-500">{{ $hint }}</p>
    @endif
</div>
