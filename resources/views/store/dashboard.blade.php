<x-client-layout>
    @php
        $hour = now()->hour;
        $greeting = $hour < 12 ? 'Buenos días' : ($hour < 19 ? 'Buenas tardes' : 'Buenas noches');
        $firstName = explode(' ', trim(Auth::user()->name))[0];

        $slides = [
            [
                'image' => 'promotionals/Hamburgesa.png',
                'title' => 'Varios negocios, un solo carrito',
                'text' => 'Cada negocio recibe su parte del pedido por separado.',
            ],
            [
                'image' => 'promotionals/Tacos.png',
                'title' => 'Tacos, burritos y más',
                'text' => 'De los negocios del campus, listos para pedir.',
            ],
            [
                'image' => 'promotionals/Bobbas.png',
                'title' => 'Postres y bebidas frías',
                'text' => 'Para el calor entre clases.',
            ],
        ];
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-10 pb-16">

        {{-- ===================== SALUDO ===================== --}}
        <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-tight text-gray-900 leading-[1.05]">
            {{ $greeting }}, {{ $firstName }}.<br>
            <span class="text-gray-400">¿Qué se te antoja hoy?</span>
        </h1>

        {{-- ===================== BANNER / CARRUSEL ===================== --}}
        <section aria-label="Promociones" class="mt-6 sm:mt-8" x-data="{
            current: 0,
            total: {{ count($slides) }},
            autoplay: null,
            start() {
                if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
                this.autoplay = setInterval(() => this.next(), 6000);
            },
            stop() { clearInterval(this.autoplay); },
            next() { this.current = (this.current + 1) % this.total; },
        }" x-init="start()" @mouseenter="stop()" @mouseleave="start()">
            <div class="relative overflow-hidden rounded-3xl bg-gray-100 aspect-[16/9] sm:aspect-[2/1] lg:aspect-[3/1]">
                @foreach ($slides as $i => $slide)
                    <div x-show="current === {{ $i }}" @if ($i > 0) style="display: none;" @endif
                        x-transition:enter="transition-opacity ease-out duration-500" x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300 absolute inset-0"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="absolute inset-0">
                        <img src="{{ asset($slide['image']) }}" alt="" class="size-full object-cover object-right">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/55 via-black/20 to-transparent"></div>
                        <div class="absolute inset-y-0 left-0 flex max-w-[58%] sm:max-w-md flex-col justify-end sm:justify-center p-5 sm:p-10">
                            <h2 class="font-display text-xl sm:text-4xl font-extrabold leading-tight text-white">
                                {{ $slide['title'] }}</h2>
                            <p class="mt-2 text-sm sm:text-base text-white/85 line-clamp-2">{{ $slide['text'] }}</p>
                        </div>
                    </div>
                @endforeach

                <div class="absolute bottom-4 right-5 flex gap-1.5">
                    @foreach ($slides as $i => $slide)
                        <button type="button" @click="current = {{ $i }}" aria-label="Ver promoción {{ $i + 1 }}"
                            :class="current === {{ $i }} ? 'bg-white w-6' : 'bg-white/50 w-2 hover:bg-white/80'"
                            class="h-2 rounded-full transition-all duration-300"></button>
                    @endforeach
                </div>
            </div>
        </section>

        @livewire('client.dashboard')
    </div>
</x-client-layout>
