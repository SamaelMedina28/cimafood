<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=bricolage-grotesque:600,700,800|figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-white text-gray-900">
        <x-banner />

        <div class="min-h-screen lg:flex">
            @livewire('navigation-menu')

            <div class="flex-1 min-w-0">
                <!-- Page Heading -->
                @if (isset($header))
                    <header class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-10 pt-6 sm:pt-10">
                        {{ $header }}
                    </header>
                @endif

                <!-- Page Content -->
                <main class="pb-16">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
