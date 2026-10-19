@props(['title', 'description' => null, 'back' => null, 'backLabel' => 'Volver'])

<div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" wire:navigate
                class="mb-2 inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-900">
                <x-icon name="chevron-left" class="size-4" />
                {{ $backLabel }}
            </a>
        @endif
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900 truncate">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 text-gray-500">{{ $description }}</p>
        @endif
    </div>

    @if (isset($actions))
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            {{ $actions }}
        </div>
    @endif
</div>
