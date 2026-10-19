@props(['id', 'name' => null, 'autocomplete' => 'current-password'])

<div x-data="{ visible: false }" class="relative mt-1.5">
    <x-input :id="$id" name="{{ $name ?? $id }}" ::type="visible ? 'text' : 'password'" type="password"
        autocomplete="{{ $autocomplete }}" {{ $attributes->merge(['class' => 'block w-full pr-11']) }} />
    <button type="button" @click="visible = !visible"
        :aria-label="visible ? 'Ocultar contraseña' : 'Mostrar contraseña'"
        class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-gray-700 focus:outline-none focus-visible:text-gray-900">
        <x-icon name="eye" class="size-5" x-show="!visible" />
        <x-icon name="eye-slash" class="size-5" x-show="visible" x-cloak />
    </button>
</div>
