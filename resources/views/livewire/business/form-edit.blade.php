<div class="space-y-6">
    <form wire:submit="update" class="space-y-8">
        <!-- Header Section: Banner & Logo -->
        <div class="relative">
            <!-- Banner -->
            <div
                class="relative group h-48 md:h-64 w-full overflow-hidden rounded-t-xl bg-yellow-500 shadow-lg border border-gray-200">
                @if ($updateBusiness->banner)
                    @if (is_string($updateBusiness->banner))
                        <img src="{{ Storage::url($updateBusiness->banner) }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ $updateBusiness->banner->temporaryUrl() }}" class="w-full h-full object-cover">
                    @endif
                @else
                    <div class="w-full h-full flex items-center justify-center">
                        <svg class="w-16 h-16 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                @endif

                <!-- Banner Upload Overlay -->
                <label for="banner"
                    class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <div class="text-center text-white">
                        <svg class="mx-auto h-8 w-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span class="text-sm font-medium">Cambiar Banner</span>
                    </div>
                </label>
                <input type="file" id="banner" wire:model="updateBusiness.banner" class="hidden"
                    accept="image/*" />

                <!-- Loading State for Banner -->
                <div wire:loading wire:target="updateBusiness.banner"
                    class="absolute inset-0 bg-white/60 flex items-center justify-center backdrop-blur-sm">
                    <div class="flex items-center space-x-2 text-indigo-600">
                        <svg class="animate-spin h-5 w-5" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span class="font-medium">Subiendo...</span>
                    </div>
                </div>
            </div>

            <!-- Logo -->
            <div class="absolute -bottom-16 inset-x-0 flex justify-center">
                <div class="relative group">
                    <div
                        class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-xl overflow-hidden bg-white">
                        @if ($updateBusiness->logo)
                            @if (is_string($updateBusiness->logo))
                                <img src="{{ Storage::url($updateBusiness->logo) }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ $updateBusiness->logo->temporaryUrl() }}"
                                    class="w-full h-full object-cover">
                            @endif
                        @else
                            <div
                                class="w-full h-full flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-full">
                                <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                        @endif

                        <!-- Logo Upload Overlay -->
                        <label for="logo"
                            class="absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-full">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </label>
                    </div>
                    <input type="file" id="logo" wire:model="updateBusiness.logo" class="hidden"
                        accept="image/*" />

                    <!-- Loading State for Logo -->
                    <div wire:loading wire:target="updateBusiness.logo"
                        class="absolute inset-0 rounded-full bg-white/60 flex items-center justify-center backdrop-blur-sm">
                        <svg class="animate-spin h-6 w-6 text-indigo-600" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-12 px-4 md:px-6">
            <!-- Validation Errors for Images -->
            @error('updateBusiness.logo')
                <p class="text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror
            @error('updateBusiness.banner')
                <p class="text-sm text-red-600 text-center">{{ $message }}</p>
            @enderror

            <!-- Basic Info Title -->
            <div class="border-b border-gray-100 pb-4">
                <h3 class="text-xl font-semibold text-gray-900">Información del Negocio</h3>
                <p class="text-sm text-gray-500 mt-1">Completa los detalles para dar a conocer tu local.</p>
            </div>

            <!-- Form Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <!-- Name -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-8 gap-y-6 md:col-span-2">
                    <div class="md:col-span-2">
                        <x-label for="name" value="Nombre del Negocio" class="text-gray-700 font-medium mb-1.5" />
                        <x-input type="text" id="name" wire:model="updateBusiness.name"
                            placeholder="Ej: Restaurante El Sabor"
                            class="w-full h-11 transition-all focus:ring-2 focus:ring-indigo-100" required />
                    </div>
                    <div>
                        <x-label for="status" value="Estado del Negocio" class="text-gray-700 font-medium mb-1.5" />
                        <select id="status" wire:model="updateBusiness.status"
                            class="w-full h-11 transition-all border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm focus:ring-indigo-100">
                            <option value="active">Activo</option>
                            <option value="inactive">Inactivo</option>
                        </select>
                        <x-input-error for="updateBusiness.status" class="mt-1" />
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <x-label for="phone" value="Teléfono de Contacto" class="text-gray-700 font-medium mb-1.5" />
                    <div class="relative">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <x-input type="tel" id="phone" wire:model="updateBusiness.phone"
                            placeholder="+1 234 567 8900"
                            class="w-full h-11 pl-10 transition-all focus:ring-2 focus:ring-indigo-100" required />
                    </div>
                    <x-input-error for="updateBusiness.phone" class="mt-1" />
                </div>

                <!-- Hours Section -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <x-label for="open_time" value="Apertura" class="text-gray-700 font-medium mb-1.5" />
                        <input type="time" id="open_time" wire:model="updateBusiness.open_time"
                            class="w-full h-11 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg shadow-sm"
                            required />
                        <x-input-error for="updateBusiness.open_time" class="mt-1" />
                    </div>
                    <div>
                        <x-label for="close_time" value="Cierre" class="text-gray-700 font-medium mb-1.5" />
                        <input type="time" id="close_time" wire:model="updateBusiness.close_time"
                            class="w-full h-11 border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg shadow-sm"
                            required />
                        <x-input-error for="updateBusiness.close_time" class="mt-1" />
                    </div>
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <x-label for="description" value="Descripción" class="text-gray-700 font-medium mb-1.5" />
                    <textarea id="description" wire:model="updateBusiness.description"
                        placeholder="Cuéntanos sobre tu negocio, especialidades, etc..."
                        class="w-full border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 rounded-lg shadow-sm p-4 transition-all"
                        rows="4" required></textarea>
                    <x-input-error for="updateBusiness.description" class="mt-1" />
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-4">
                <a href="{{ route('business.index') }}" wire:navigate
                    class="px-6 py-2 rounded-full text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-50 transition-colors">
                    Cancelar
                </a>
                <x-button type="submit" wire:loading.attr="disabled">
                    <span wire:loading.remove>Actualizar Negocio</span>
                    <span wire:loading class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Procesando...
                    </span>
                </x-button>
            </div>
        </div>
    </form>
</div>
