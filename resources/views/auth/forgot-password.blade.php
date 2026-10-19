<x-guest-layout>
    <x-authentication-card title="Recupera tu contraseña"
        description="Escribe el correo de tu cuenta y te enviaremos un enlace para crear una nueva.">
        @session('status')
            <x-auth-status class="mb-6">{{ $value }}</x-auth-status>
        @endsession

        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <x-label for="email" value="Correo electrónico" />
                <x-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="tu@correo.com" />
            </div>

            <x-button class="w-full py-3 text-base">Enviar enlace</x-button>
        </form>

        <a href="{{ route('login') }}" class="mt-8 inline-flex items-center gap-1 text-sm font-semibold text-green-700 hover:text-green-800">
            <x-icon name="chevron-left" class="size-4" />
            Volver a iniciar sesión
        </a>
    </x-authentication-card>
</x-guest-layout>
