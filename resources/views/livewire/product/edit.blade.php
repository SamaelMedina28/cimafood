<div class="space-y-6">
    <form wire:submit="update" class="space-y-8">
        <!-- Top Section: Image + Main Inputs -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Image (más pequeña y cuadrada) -->
            <div class="md:col-span-1">
                <div
                    class="relative group w-full aspect-square overflow-hidden rounded-xl bg-yellow-500 shadow-lg border border-gray-200">

                    @if ($form->image_path)
                        @if (is_string($form->image_path))
                            <img src="{{ asset('storage/' . $form->image_path) }}" class="w-full h-full object-cover">
                        @elseif (str_starts_with($form->image_path->getMimeType(), 'image/'))
                            <img src="{{ $form->image_path->temporaryUrl() }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    @endif

                    <label for="image_path"
                        class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                        <div class="text-center text-white">
                            <span class="text-sm font-medium">Cambiar</span>
                        </div>
                    </label>

                    <input type="file" id="image_path" wire:model="form.image_path" class="hidden" accept="image/*" />
                </div>

                @error('form.image_path')
                    <p class="text-sm text-red-600 text-center mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Inputs principales -->
            <div class="md:col-span-2 grid grid-cols-1">

                <!-- Nombre -->
                <div class="p-0">
                    <x-label for="name" value="Nombre del Producto" />
                    <x-input type="text" id="name" wire:model="form.name" class="w-full h-11" required />
                    <x-input-error for="form.name" />
                </div>

                <!-- Negocio -->
                <div class="grid grid-cols-2 gap-4 ">

                    <div class="p-0">
                        <x-label for="business_id" value="Negocio" />
                        <select id="business_id" wire:model="form.business_id"
                            class="w-full h-11 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            <option value="">Selecciona el negocio</option>
                            @foreach ($businesses as $business)
                                <option value="{{ $business->id }}">{{ $business->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error for="form.business_id" />
                    </div>

                    <!-- Estado -->
                    <div>
                        <x-label for="status" value="Estado" />
                        <select id="status" wire:model="form.status" class="w-full h-11 border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" required>
                            <option value="">Selecciona estado</option>
                            <option value="available">Disponible</option>
                            <option value="unavailable">No disponible</option>
                        </select>
                        <x-input-error for="form.status" />
                    </div>
                </div>

                <!-- Precio + Stock -->
                <div class="grid grid-cols-2 gap-4 ">
                    <div>
                        <x-label for="price" value="Precio" />
                        <x-input type="number" step="0.01" id="price" wire:model="form.price"
                            class="w-full h-11" required />
                        <x-input-error for="form.price" />
                    </div>

                    <div>
                        <x-label for="quantity" value="Stock" />
                        <x-input type="number" id="quantity" wire:model="form.quantity" class="w-full h-11"
                            required />
                        <x-input-error for="form.quantity" />
                    </div>
                </div>
                <!-- Descripción abajo -->
                <div>
                    <x-label for="description" value="Descripción" />
                    <textarea id="description" wire:model="form.description" class="w-full border-gray-300 rounded-lg p-4" rows="3"
                        required></textarea>
                    <x-input-error for="form.description" />
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('product.index') }}" wire:navigate
                class="px-6 py-2 rounded-full text-sm font-medium text-gray-600 border border-gray-300 hover:bg-gray-50 transition-colors">
                Cancelar
            </a>

            <x-button type="submit">
                Guardar Cambios
            </x-button>
        </div>

    </form>
</div>
