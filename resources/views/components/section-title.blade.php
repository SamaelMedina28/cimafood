<div class="md:col-span-1 flex justify-between">
    <div>
        <h2 class="font-display text-lg font-bold text-gray-900">{{ $title }}</h2>

        <p class="mt-1 text-sm text-gray-500">
            {{ $description }}
        </p>
    </div>

    <div>
        {{ $aside ?? '' }}
    </div>
</div>
