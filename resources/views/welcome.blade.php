<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'CimaFood') }}</title>
    <meta name="description" content="Pide comida de los negocios del campus de la UABC desde tu teléfono.">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-white font-sans text-gray-900 antialiased">
    @php
        $home = auth()->check() ? (auth()->user()->is_vendor ? route('dashboard') : route('store')) : null;
        $photos = [
            'example_images/products/tacos/img1.jpg',
            'example_images/products/hamburgesa/img3.jpg',
            'example_images/products/sushi/img2.jpg',
            'example_images/products/burritos/img1.jpg',
        ];
    @endphp

    {{-- Navegación --}}
    <header class="max-w-6xl mx-auto flex h-20 items-center justify-between px-4 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <img src="{{ asset('Logo-uabc.png') }}" alt="" class="size-9 object-contain">
            <span class="font-display text-xl font-extrabold tracking-tight">Cima<span class="text-green-600">Food</span></span>
        </a>
        <nav class="flex items-center gap-2">
            @if ($home)
                <a href="{{ $home }}" class="rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">
                    {{ auth()->user()->is_vendor ? 'Ir a mi panel' : 'Ir a la tienda' }}
                </a>
            @else
                <a href="{{ route('login') }}" class="rounded-full px-4 py-2.5 text-sm font-semibold text-gray-900 hover:bg-gray-100">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="hidden sm:inline-flex rounded-full bg-green-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-green-700">Crear cuenta</a>
            @endif
        </nav>
    </header>

    <main>
        {{-- Portada --}}
        <section class="max-w-6xl mx-auto grid grid-cols-1 lg:grid-cols-2 items-center gap-12 px-4 sm:px-6 lg:px-8 pt-8 pb-20 lg:pt-16">
            <div>
                <h1 class="font-display text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[0.95]">
                    La comida del campus, en un solo carrito.
                </h1>
                <p class="mt-6 max-w-md text-lg text-gray-600">
                    Pide en los negocios de la UABC desde tu teléfono, de uno o de varios a la vez, y sigue el estado de cada pedido.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    @if ($home)
                        <a href="{{ $home }}" class="rounded-full bg-green-600 px-7 py-3.5 font-semibold text-white hover:bg-green-700">
                            {{ auth()->user()->is_vendor ? 'Ir a mi panel' : 'Ver negocios' }}</a>
                    @else
                        <a href="{{ route('register') }}" class="rounded-full bg-green-600 px-7 py-3.5 font-semibold text-white hover:bg-green-700">Crear cuenta</a>
                        <a href="{{ route('login') }}" class="rounded-full border border-gray-200 px-7 py-3.5 font-semibold text-gray-900 hover:border-gray-300 hover:bg-gray-50">Ya tengo cuenta</a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4" aria-hidden="true">
                @foreach ($photos as $i => $photo)
                    <div @class(['overflow-hidden rounded-3xl bg-gray-100', 'aspect-[4/5]', 'translate-y-8' => $i % 2 === 1])>
                        <img src="{{ asset($photo) }}" alt="" class="size-full object-cover">
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Negocios --}}
        @if ($businesses->count() > 0)
            <section class="border-t border-gray-100 bg-gray-50/60">
                <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight">Ya están en CimaFood</h2>
                    <p class="mt-2 text-gray-600">Algunos de los negocios donde puedes pedir.</p>

                    <ul class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($businesses as $business)
                            <li class="flex items-center gap-4 rounded-2xl bg-white p-4 ring-1 ring-gray-200">
                                <span class="size-14 shrink-0 overflow-hidden rounded-full bg-green-50">
                                    @if ($business->logo_url)
                                        <img src="{{ $business->logo_url }}" alt="" class="size-full object-cover">
                                    @endif
                                </span>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $business->name }}</p>
                                    <p class="text-sm text-gray-500 tabular-nums">{{ substr($business->open_time, 0, 5) }} a {{ substr($business->close_time, 0, 5) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        {{-- Para negocios --}}
        <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex flex-col gap-8 rounded-3xl bg-green-600 p-8 sm:p-12 text-white lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-xl">
                    <h2 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight">¿Tienes un negocio en el campus?</h2>
                    <p class="mt-3 text-green-50">Publica tu menú, controla tu stock y recibe pedidos desde un panel con tus ventas del día, la semana y el mes.</p>
                </div>
                <a href="{{ route('register', ['rol' => 'vendedor']) }}"
                    class="shrink-0 self-start lg:self-auto rounded-full bg-white px-7 py-3.5 font-semibold text-green-800 hover:bg-green-50">
                    Registrar mi negocio
                </a>
            </div>
        </section>
    </main>

    <footer class="border-t border-gray-100">
        <div class="max-w-6xl mx-auto flex flex-col gap-2 px-4 sm:px-6 lg:px-8 py-8 text-sm text-gray-500 sm:flex-row sm:justify-between">
            <p>© {{ date('Y') }} CimaFood</p>
            <p>Universidad Autónoma de Baja California</p>
        </div>
    </footer>
</body>
</html>
