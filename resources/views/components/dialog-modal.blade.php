@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 pt-6 pb-4">
        <div class="font-display text-xl font-bold text-gray-900">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-600">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-row justify-end gap-3 px-6 py-4 border-t border-gray-100 text-end">
        {{ $footer }}
    </div>
</x-modal>
