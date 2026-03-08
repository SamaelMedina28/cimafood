<div class="space-y-6">
    <form wire:submit="store" class="space-y-6">
        <!-- Información Básica -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Información Básica</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="name" value="Nombre del Negocio" />
                    <x-input type="text" id="name" name="name" wire:model="name"
                        placeholder="Ej: Restaurante El Sabor" class="w-full" required />
                    <x-input-error for="name" class="mt-1" />
                </div>
                <div>
                    <x-label for="phone" value="Teléfono" />
                    <x-input type="tel" id="phone" name="phone" wire:model="phone"
                        placeholder="Ej: +1 234 567 8900" class="w-full" required />
                    <x-input-error for="phone" class="mt-1" />
                </div>
                <div>
                    <x-label for="open_time" value="Hora de Apertura" />
                    <input type="time" id="open_time" name="open_time" wire:model="open_time"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required />
                    <x-input-error for="open_time" class="mt-1" />
                </div>
                <div>
                    <x-label for="close_time" value="Hora de Cierre" />
                    <input type="time" id="close_time" name="close_time" wire:model="close_time"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required />
                    <x-input-error for="close_time" class="mt-1" />
                </div>
            </div>
            <div class="mt-4">
                <x-label for="description" value="Descripción" />
                <textarea id="description" name="description" wire:model="description"
                    placeholder="Describe tu negocio, tipo de comida, especialidades..."
                    class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3"
                    required></textarea>
                <x-input-error for="description" class="mt-1" />
            </div>
        </div>

        <!-- Imágenes -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Imágenes</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <x-label for="logo" value="Logo" />
                    <input type="file" id="logo" name="logo" wire:model="logo"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        accept="image/*" />
                    <p class="mt-1 text-sm text-gray-500">Formato recomendado: PNG o JPG, máximo 2MB</p>
                    <x-input-error for="logo" class="mt-1" />
                </div>
                <div>
                    <x-label for="banner" value="Banner" />
                    <input type="file" id="banner" name="banner" wire:model="banner"
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        accept="image/*" />
                    <p class="mt-1 text-sm text-gray-500">Formato recomendado: 1920x400px, máximo 5MB</p>
                    <x-input-error for="banner" class="mt-1" />
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
            <a href="{{ route('business.index') }}"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                Cancelar
            </a>
            <x-button type="submit" wire:loading.attr="disabled">
                <span wire:loading.remove>Crear Negocio</span>
                <span wire:loading class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    Procesando...
                </span>
            </x-button>
        </div>
    </form>
</div>
