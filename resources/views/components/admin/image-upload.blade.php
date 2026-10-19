@props([
    // Propiedad de Livewire a la que se enlaza el input (ej. 'form.image_path')
    'model',
    // Valor actual: ruta/URL guardada o archivo temporal recién subido
    'file' => null,
    // 'banner' (16:5), 'logo' (círculo) o 'square'
    'shape' => 'square',
    'label',
    'hint' => 'JPG o PNG',
])

@php
    $previewUrl = null;
    if ($file instanceof \Livewire\Features\SupportFileUploads\TemporaryUploadedFile) {
        $previewUrl = $file->isPreviewable() ? $file->temporaryUrl() : null;
    } elseif (is_string($file) && $file !== '') {
        $previewUrl = \App\Models\Business::imageUrl($file);
    }

    $id = str_replace('.', '-', $model);
@endphp

<div {{ $attributes }}>
    <label for="{{ $id }}" @class([
        'group relative block cursor-pointer overflow-hidden bg-gray-50 ring-1 ring-gray-200 transition hover:ring-2 hover:ring-green-600 focus-within:ring-2 focus-within:ring-green-600',
        'aspect-[16/5] w-full rounded-2xl' => $shape === 'banner',
        'size-28 rounded-full' => $shape === 'logo',
        'aspect-square w-full rounded-2xl' => $shape === 'square',
    ])>
        @if ($previewUrl)
            <img src="{{ $previewUrl }}" alt="" class="size-full object-cover">
            <span class="absolute inset-0 flex items-center justify-center bg-gray-900/50 text-sm font-semibold text-white opacity-0 transition-opacity group-hover:opacity-100">
                Cambiar
            </span>
        @else
            <span class="flex size-full flex-col items-center justify-center gap-1 p-3 text-center text-gray-500">
                <x-icon name="photo" class="size-7 text-gray-400" />
                @if ($shape !== 'logo')
                    <span class="text-sm font-medium text-gray-700">Subir {{ mb_strtolower($label) }}</span>
                    <span class="text-xs">{{ $hint }}</span>
                @endif
            </span>
        @endif

        <span wire:loading.flex wire:target="{{ $model }}"
            class="absolute inset-0 items-center justify-center bg-white/70 text-sm font-medium text-gray-700 backdrop-blur-sm">
            Subiendo
        </span>

        <input type="file" id="{{ $id }}" wire:model="{{ $model }}" class="sr-only" accept="image/*">
    </label>
    <p class="mt-2 text-sm font-medium text-gray-900 {{ $shape === 'logo' ? 'text-center' : '' }}">{{ $label }}</p>
    <x-input-error :for="$model" class="mt-1" />
</div>
