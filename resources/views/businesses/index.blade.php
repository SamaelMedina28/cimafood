<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Businesses') }}
    </h2>
  </x-slot>
  <div>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="flex justify-end my-5">
        <a href="{{ route('business.create') }}"
          class="inline-flex items-center px-4 py-2 bg-green-700 border border-transparent rounded-full font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:bg-green-600 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 transition ease-in-out duration-150"
          wire:navigate>
          Agregar negocio

        </a>
      </div>
      <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        @if ($businesses->count() === 0)
          <div class="flex flex-col items-center justify-center py-16 px-6 text-center">
            <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4 border border-gray-100">
              <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 tracking-tight">Aún no hay negocios</h3>
            <p class="mt-2 text-sm text-gray-500 max-w-sm">Registra tu primer negocio para empezar a
              gestionar tus productos y pedidos de forma sencilla.</p>
          </div>
        @else
          <ul class="divide-y divide-gray-100">
            @foreach ($businesses as $business)
              <li class="p-5 sm:p-6 hover:bg-gray-50/50 transition-colors duration-200 group">
                <div class="flex items-center space-x-5">
                  <div class="flex-shrink-0">
                    @if ($business->logo)
                      <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ $business->name }}"
                        class="w-16 h-16 rounded-2xl object-cover border border-gray-100 shadow-sm">
                    @else
                      <div
                        class="w-16 h-16 rounded-2xl bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 flex items-center justify-center shadow-sm">
                        <span
                          class="text-2xl font-semibold text-gray-400">{{ strtoupper(substr($business->name, 0, 1)) }}</span>
                      </div>
                    @endif
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                      <p
                        class="text-base font-semibold text-gray-900 truncate tracking-tight group-hover:text-green-700 transition-colors">
                        @if (isset($business->status) && $business->status === 'active')
                          <span
                            class="inline-flex items-center px-1.5 py-1.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                          </span>
                        @elseif(isset($business->status) && $business->status !== 'active')
                          <span
                            class="inline-flex items-center px-1.5 py-1.5 rounded-full text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                          </span>
                        @endif
                        {{ $business->name }}
                      </p>
                      <div class="flex items-center space-x-3">

                        <div class="flex items-center gap-2">
                          {{-- Botón Ver --}}
                          <a href="{{ route('business.show', $business) }}"
                            wire:navigate
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-slate-600 bg-white rounded-full hover:bg-slate-100 hover:text-slate-900 hover:border-slate-200 transition-all duration-150">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                              stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Ver
                          </a>

                          {{-- Botón Editar --}}
                          <a href="#"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-white bg-yellow-500 rounded-full shadow-sm hover:bg-yellow-600 active:bg-yellow-700 transition-all duration-150">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                              stroke-width="2">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Editar
                          </a>
                        </div>
                      </div>
                    </div>
                    <div class="mt-1.5 flex items-center text-sm text-gray-500 space-x-4">
                      @if ($business->phone)
                        <span class="flex items-center">
                          <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                          </svg>
                          {{ $business->phone }}
                        </span>
                      @endif

                      <span class="flex items-center truncate max-w-[200px] sm:max-w-xs">
                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor"
                          viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                          </path>
                        </svg>
                        {{ $business->description ? Str::limit($business->description, 50) : 'Sin descripción' }}
                      </span>
                    </div>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </div>
</x-app-layout>
