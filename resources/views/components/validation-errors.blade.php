@if ($errors->any())
    <div {{ $attributes->merge(['class' => 'rounded-xl bg-red-50 p-4 text-sm text-red-800']) }} role="alert">
        @if ($errors->count() === 1)
            <p class="flex gap-2"><x-icon name="warning" class="size-5 shrink-0" />{{ $errors->first() }}</p>
        @else
            <p class="flex gap-2 font-medium"><x-icon name="warning" class="size-5 shrink-0" />Revisa lo siguiente:</p>
            <ul class="mt-2 ml-7 list-disc space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
