{{-- Requiere: $form, $businesses, $submitAction, $submitLabel --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <x-admin.image-upload model="form.image_path" :file="$form->image_path" shape="square" label="Foto del producto"
        hint="Cuadrada, mínimo 800 px" class="max-w-xs lg:max-w-none" />

    <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5 content-start">
        <div class="sm:col-span-2">
            <x-label for="name" value="Nombre" />
            <x-input type="text" id="name" wire:model="form.name" placeholder="Ej. Tacos de pastor (orden de 3)"
                class="mt-1.5 w-full" required />
            <x-input-error for="form.name" class="mt-1" />
        </div>

        <div class="sm:col-span-2">
            <x-label for="description" value="Descripción" />
            <textarea id="description" wire:model="form.description" rows="3" required maxlength="255"
                placeholder="Ingredientes, tamaño o lo que incluye"
                class="mt-1.5 w-full rounded-lg border-gray-200 shadow-sm placeholder:text-gray-400 focus:border-green-600 focus:ring-green-600"></textarea>
            <x-input-error for="form.description" class="mt-1" />
        </div>

        <div>
            <x-label for="price" value="Precio" />
            <div class="relative mt-1.5">
                <span class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">$</span>
                <x-input type="number" step="0.01" min="0" id="price" wire:model="form.price" placeholder="0.00"
                    class="w-full pl-7 tabular-nums" required />
            </div>
            <x-input-error for="form.price" class="mt-1" />
        </div>

        <div>
            <x-label for="quantity" value="Stock disponible" />
            <x-input type="number" min="0" id="quantity" wire:model="form.quantity" placeholder="0"
                class="mt-1.5 w-full tabular-nums" required />
            <x-input-error for="form.quantity" class="mt-1" />
        </div>

        <div>
            <x-label for="business_id" value="Negocio" />
            <select id="business_id" wire:model="form.business_id" required
                class="mt-1.5 w-full rounded-lg border-gray-200 shadow-sm focus:border-green-600 focus:ring-green-600">
                <option value="">Elige un negocio</option>
                @foreach ($businesses as $business)
                    <option value="{{ $business->id }}">{{ $business->name }}</option>
                @endforeach
            </select>
            <x-input-error for="form.business_id" class="mt-1" />
        </div>

        <div>
            <x-label for="status" value="Disponibilidad" />
            <select id="status" wire:model="form.status" required
                class="mt-1.5 w-full rounded-lg border-gray-200 shadow-sm focus:border-green-600 focus:ring-green-600">
                <option value="">Elige una opción</option>
                <option value="available">Disponible para pedir</option>
                <option value="unavailable">No disponible</option>
            </select>
            <x-input-error for="form.status" class="mt-1" />
        </div>

        <div class="sm:col-span-2 flex items-center justify-end gap-3 border-t border-gray-100 pt-6 mt-2">
            <a href="{{ route('product.index') }}" wire:navigate
                class="rounded-full px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-100">Cancelar</a>
            <x-button type="submit" wire:loading.attr="disabled" wire:target="{{ $submitAction }}">
                <span wire:loading.remove wire:target="{{ $submitAction }}">{{ $submitLabel }}</span>
                <span wire:loading wire:target="{{ $submitAction }}">Guardando</span>
            </x-button>
        </div>
    </div>
</div>
