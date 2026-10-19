{{-- Mensaje de éxito en las pantallas de acceso (ej. "te enviamos el enlace") --}}
<div {{ $attributes->merge(['class' => 'flex gap-2 rounded-xl bg-green-50 p-4 text-sm text-green-800']) }} role="status">
    <x-icon name="check" class="size-5 shrink-0" />
    <span>{{ $slot }}</span>
</div>
