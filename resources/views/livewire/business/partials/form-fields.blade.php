{{-- Requiere: $form (objeto del formulario), $prefix (nombre de la propiedad en Livewire), $submitLabel --}}
<div class="space-y-10">
    {{-- Imágenes --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            <h2 class="font-display text-lg font-bold text-gray-900">Imagen del negocio</h2>
            <p class="mt-1 text-sm text-gray-500">La portada y el logo aparecen en la tienda y en el menú del negocio.</p>
        </div>
        <div class="lg:col-span-2 space-y-6">
            <x-admin.image-upload :model="$prefix . '.banner'" :file="$form->banner" shape="banner" label="Portada"
                hint="Horizontal, mínimo 1600 px de ancho" />
            <x-admin.image-upload :model="$prefix . '.logo'" :file="$form->logo" shape="logo" label="Logo" class="w-28" />
        </div>
    </section>

    {{-- Datos --}}
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 border-t border-gray-100 pt-10">
        <div>
            <h2 class="font-display text-lg font-bold text-gray-900">Información</h2>
            <p class="mt-1 text-sm text-gray-500">Así es como los clientes encuentran y contactan tu negocio.</p>
        </div>
        <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div class="sm:col-span-2">
                <x-label for="name" value="Nombre" />
                <x-input type="text" id="name" wire:model="{{ $prefix }}.name" placeholder="Ej. Tacos El Cimarrón"
                    class="mt-1.5 w-full" required />
                <x-input-error for="{{ $prefix }}.name" class="mt-1" />
            </div>

            <div class="sm:col-span-2">
                <x-label for="description" value="Descripción" />
                <textarea id="description" wire:model="{{ $prefix }}.description" rows="3" required
                    placeholder="Qué vendes y qué te distingue"
                    class="mt-1.5 w-full rounded-lg border-gray-200 shadow-sm placeholder:text-gray-400 focus:border-green-600 focus:ring-green-600"></textarea>
                <x-input-error for="{{ $prefix }}.description" class="mt-1" />
            </div>

            <div>
                <x-label for="phone" value="Teléfono" />
                <x-input type="tel" id="phone" wire:model="{{ $prefix }}.phone" placeholder="686 123 4567"
                    class="mt-1.5 w-full" required />
                <x-input-error for="{{ $prefix }}.phone" class="mt-1" />
            </div>

            @if (property_exists($form, 'status'))
                <div>
                    <x-label for="status" value="Visibilidad" />
                    <select id="status" wire:model="{{ $prefix }}.status"
                        class="mt-1.5 w-full rounded-lg border-gray-200 shadow-sm focus:border-green-600 focus:ring-green-600">
                        <option value="active">Visible en la tienda</option>
                        <option value="inactive">Oculto de la tienda</option>
                    </select>
                    <x-input-error for="{{ $prefix }}.status" class="mt-1" />
                </div>
            @endif

            <div class="sm:col-span-2 grid grid-cols-2 gap-5">
                <div>
                    <x-label for="open_time" value="Abre a las" />
                    <x-input type="time" id="open_time" wire:model="{{ $prefix }}.open_time" class="mt-1.5 w-full" required />
                    <x-input-error for="{{ $prefix }}.open_time" class="mt-1" />
                </div>
                <div>
                    <x-label for="close_time" value="Cierra a las" />
                    <x-input type="time" id="close_time" wire:model="{{ $prefix }}.close_time" class="mt-1.5 w-full" required />
                    <x-input-error for="{{ $prefix }}.close_time" class="mt-1" />
                </div>
            </div>
        </div>
    </section>

    {{-- Acciones --}}
    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-6">
        <a href="{{ route('business.index') }}" wire:navigate
            class="rounded-full px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancelar</a>
        <x-button type="submit" wire:loading.attr="disabled" wire:target="{{ $submitAction }}">
            <span wire:loading.remove wire:target="{{ $submitAction }}">{{ $submitLabel }}</span>
            <span wire:loading wire:target="{{ $submitAction }}">Guardando</span>
        </x-button>
    </div>
</div>
