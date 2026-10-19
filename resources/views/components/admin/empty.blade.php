@props(['icon' => 'photo', 'title'])

<div {{ $attributes->class('px-6 py-16 text-center') }}>
    <x-icon :name="$icon" class="mx-auto size-10 text-gray-300" />
    <h3 class="mt-4 font-display text-lg font-bold text-gray-900">{{ $title }}</h3>
    @if ($slot->isNotEmpty())
        <div class="mx-auto mt-1 max-w-sm text-sm text-gray-500">{{ $slot }}</div>
    @endif
</div>
