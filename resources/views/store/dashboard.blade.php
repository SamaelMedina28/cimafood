<x-client-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <div class="flex items-center gap-2" x-data="{ open: false }">
                <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
                <input x-show="open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 w-0" x-transition:enter-end="opacity-100 w-48"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 w-48"
                    x-transition:leave-end="opacity-0 w-0" type="text" placeholder="Buscar..."
                    class="w-48 rounded-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm overflow-hidden" />
            </div>
            <button class="relative p-2 rounded-full hover:bg-gray-100 transition-all duration-200">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 3h2l.4 2M7 13h10l4-4H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </button>
        </div>
    </x-slot>

    <div class="pb-10">

        {{-- ===================== BANNER / CARRUSEL ===================== --}}
        <div x-data="{
        current: 0,
        total: 3,
        autoplay: null,
        start() {
            this.autoplay = setInterval(() => {
                this.current = (this.current + 1) % this.total
            }, 4000)
        }
    }" x-init="start()" class="relative w-full overflow-hidden" style="height: 180px;">

            {{-- Slide 1 --}}
            <div x-show="current === 0" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-full"
                class="absolute inset-0 bg-gradient-to-r from-emerald-600 to-emerald-400 flex items-center px-8"
                style="background-image: url('{{ asset('promotionals/Hamburgesa.png') }}'); background-size: cover; background-position: center;">
                <div>
                    <span
                        class="bg-white text-emerald-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Oferta
                        del día</span>
                    <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">20% off en<br>tu primer pedido
                    </h3>
                    <p class="text-emerald-100 text-sm mt-1">Usa el código <span class="font-bold">BIENVENIDO</span></p>
                </div>
                <div class="ml-auto text-6xl">🍔</div>
            </div>

            {{-- Slide 2 --}}
            <div x-show="current === 1" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-full"
                class="absolute inset-0 bg-gradient-to-r from-orange-500 to-yellow-400 flex items-center px-8"
                style="background-image: url('{{ asset('promotionals/Tacos.png') }}'); background-size: cover; background-position: center;">
                <div>
                    <span
                        class="bg-white text-orange-500 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Nuevo</span>
                    <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">Combos desde<br>$45 pesos</h3>
                    <p class="text-orange-100 text-sm mt-1">Disponibles todo el día</p>
                </div>
                <div class="ml-auto text-6xl">🌮</div>
            </div>

            {{-- Slide 3 --}}
            <div x-show="current === 2" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-full" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="opacity-100 translate-x-0"
                x-transition:leave-end="opacity-0 -translate-x-full"
                class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-500 flex items-center px-8"
                style="background-image: url('{{ asset('promotionals/Bobbas.png') }}'); background-size: cover; background-position: center;">
                <div>
                    <span
                        class="bg-white text-purple-600 text-xs font-bold px-2 py-1 rounded-full uppercase tracking-wide">Popular</span>
                    <h3 class="text-white text-2xl font-extrabold mt-2 leading-tight">Postres y<br>bebidas frías</h3>
                    <p class="text-purple-100 text-sm mt-1">Perfectos para el calor</p>
                </div>
                <div class="ml-auto text-6xl">🧋</div>
            </div>

            {{-- Dots --}}
            <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5">
                <template x-for="i in total" :key="i">
                    <button @click="current = i - 1" :class="current === i - 1 ? 'bg-white w-4' : 'bg-white/50 w-1.5'"
                        class="h-1.5 rounded-full transition-all duration-300"></button>
                </template>
            </div>
        </div>

        @livewire('client.dashboard')
    </div>
</x-client-layout>
