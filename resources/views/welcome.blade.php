<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'CimaFood') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-100 min-h-screen flex flex-col items-center justify-center p-6 font-sans">

        {{-- Logo --}}
        <div class="mb-6">
            <img src="{{ asset('Logo-uabc.png') }}"
                 alt="Logo CimaFood UABC"
                 class="h-20 w-auto object-contain mx-auto">
        </div>

        {{-- Card --}}
        <div class="w-full max-w-sm bg-white rounded-xl shadow-md p-8">

            {{-- Title --}}
            <div class="text-center mb-6">
                <h1 class="text-lg font-semibold text-gray-900 mb-1">Bienvenido a CimaFood</h1>
                <p class="text-sm text-gray-500 leading-relaxed">
                    Plataforma de comida universitaria UABC.<br>
                    Inicia sesión o regístrate para continuar.
                </p>
            </div>

            {{-- Auth buttons --}}
            @if (Route::has('login'))
                <div class="flex flex-col gap-2">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                           class="w-full text-center py-2.5 rounded-lg bg-green-700 text-white text-sm font-semibold hover:bg-green-800 transition">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="w-full text-center py-2.5 rounded-lg bg-green-700 text-white text-sm font-semibold hover:bg-green-800 transition">
                            Iniciar sesión
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="w-full text-center py-2.5 rounded-lg border border-gray-200 text-gray-600 text-sm font-medium hover:bg-gray-50 hover:border-gray-300 transition">
                                Crear cuenta
                            </a>
                        @endif
                    @endauth
                </div>
            @endif

        </div>

        {{-- Footer --}}
        <p class="mt-6 text-xs text-gray-400">
            © {{ date('Y') }} CimaFood · Universidad Autónoma de Baja California
        </p>

    </body>
</html>
