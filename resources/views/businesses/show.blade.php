<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center gap-2">
      <a href="{{ route('business.index') }}" wire:navigate
        class="inline-flex items-center gap-1.5 px-3 text-sm font-medium text-slate-600 bg-white rounded-full">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver
      </a>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight truncate">
        {{ $business->name }}
      </h2>
    </div>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100 flex flex-col">

        <!-- Banner Superior -->
        <div class="h-48 sm:h-64 w-full bg-gradient-to-r from-emerald-500 to-green-600 relative group">
          @if ($business->banner)
            <img src="{{ asset('storage/' . $business->banner) }}" alt="Banner de {{ $business->name }}"
              class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/10 group-hover:bg-black/5 transition-colors"></div>
          @endif
        </div>

        <!-- Cuerpo Central -->
        <div class="px-6 sm:px-10 pb-10">
          <!-- Cabecera superpuesta (Logo y Botones) -->
          <div
            class="flex flex-col sm:flex-row sm:items-end sm:justify-between -mt-16 sm:-mt-20 mb-8 relative z-10 space-y-4 sm:space-y-0">

            <!-- Contenedor Logo y Título Desktop -->
            <div class="flex items-end space-x-5">
              <div
                class="flex-shrink-0 h-32 w-32 sm:h-40 sm:w-40 rounded-full border-4 border-white bg-white shadow-md overflow-hidden flex items-center justify-center">
                @if ($business->logo)
                  <img src="{{ asset('storage/' . $business->logo) }}" alt="Logo de {{ $business->name }}"
                    class="h-full w-full object-cover">
                @else
                  <div
                    class="h-full w-full bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center">
                    <span
                      class="text-5xl font-bold text-gray-300">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                  </div>
                @endif
              </div>

              <!-- Título y Status (Desktop) -->
              <div class="hidden sm:block pb-2">
                <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $business->name }}</h1>
                <div class="mt-2 flex items-center gap-3">
                  @if (isset($business->status) && $business->status === 'active')
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                      <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                      Activo
                    </span>
                  @else
                    <span
                      class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                      <span class="w-2 h-2 rounded-full bg-gray-400 mr-1.5"></span>
                      Inactivo
                    </span>
                  @endif
                </div>
              </div>
            </div>

            <!-- Botón de Acción -->
            <div class="flex pb-2">
              <a href="{{ route('business.edit', $business) }}" wire:navigate
                class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-yellow-500 rounded-full shadow-sm hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2 transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Editar Información
              </a>
            </div>
          </div>

          <!-- Título y Status (Mobile) -->
          <div class="sm:hidden mb-8">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">{{ $business->name }}</h1>
            <div class="mt-3 flex items-center gap-3">
              @if (isset($business->status) && $business->status === 'active')
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span>
                  Activo
                </span>
              @else
                <span
                  class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-50 text-gray-600 border border-gray-200">
                  <span class="w-2 h-2 rounded-full bg-gray-400 mr-1.5"></span>
                  Inactivo
                </span>
              @endif
            </div>
          </div>

          <!-- Tabs de navegación -->
          <div x-data="{ activeTab: 'info' }" class="space-y-6">
            <div class="border-b border-gray-100">
              <nav class="-mb-px flex gap-1 overflow-x-auto" aria-label="Secciones del negocio">
                <button
                  @click="activeTab = 'info'"
                  :class="activeTab === 'info'
                    ? 'border-b-2 border-green-600 text-green-700 bg-green-50/60'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                  class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all whitespace-nowrap">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  Información
                </button>
                <button
                  @click="activeTab = 'orders'"
                  :class="activeTab === 'orders'
                    ? 'border-b-2 border-green-600 text-green-700 bg-green-50/60'
                    : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50'"
                  class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold rounded-t-lg transition-all whitespace-nowrap">
                  <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  Pedidos
                  @if ($ordersCount > 0)
                    <span class="inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-green-600 text-white">
                      {{ $ordersCount > 99 ? '99+' : $ordersCount }}
                    </span>
                  @endif
                </button>
              </nav>
            </div>

            <!-- Tab: Información -->
            <div x-show="activeTab === 'info'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
              <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Descripción Central -->
                <div class="lg:col-span-2 space-y-8">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3 border-b border-gray-100 pb-2">Acerca del Negocio
                    </h3>
                    <p class="text-gray-600 leading-relaxed">
                      {{ $business->description ?? 'El propietario aún no ha proporcionado una descripción detallada para este negocio.' }}
                    </p>
                  </div>
                </div>

                <!-- Panel de Contacto Lateral -->
                <div class="space-y-6">
                  <div class="bg-gray-50/50 rounded-2xl p-6 border border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-900 tracking-wide uppercase mb-5">Detalles Relevantes</h3>

                    <ul class="space-y-5">
                      <li class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                          <div class="p-2 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Teléfono de Contacto</p>
                          <p class="text-sm text-gray-900 font-medium mt-1">{{ $business->phone ?? 'No registrado' }}</p>
                        </div>
                      </li>

                      <li class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                          <div class="p-2 bg-white rounded-lg border border-gray-100 shadow-sm">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                          </div>
                        </div>
                        <div class="ml-4">
                          <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Horario de Atención</p>
                          @if ($business->open_time && $business->close_time)
                            <p class="text-sm font-medium text-gray-900 mt-1">
                              {{ \Carbon\Carbon::parse($business->open_time)->format('h:i A') }} —
                              {{ \Carbon\Carbon::parse($business->close_time)->format('h:i A') }}
                            </p>
                          @else
                            <p class="text-sm font-medium text-gray-900 mt-1">Horarios no establecidos</p>
                          @endif
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>

              </div>
            </div>

            <!-- Tab: Pedidos -->
            <div x-show="activeTab === 'orders'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
              @livewire('business.orders', ['business' => $business])
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
