<x-guest-layout>
    <x-authentication-card title="Inicia sesión" description="Entra para pedir comida o para administrar tu negocio.">
        <x-validation-errors class="mb-6" />

        @session('status')
            <x-auth-status class="mb-6">{{ $value }}</x-auth-status>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-label for="email" value="Correo electrónico" />
                <x-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" placeholder="tu@correo.com" />
            </div>

            <div>
                <div class="flex items-baseline justify-between">
                    <x-label for="password" value="Contraseña" />
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                            class="text-sm font-medium text-green-700 hover:text-green-800 focus:outline-none focus-visible:underline">
                            ¿La olvidaste?
                        </a>
                    @endif
                </div>
                <x-password-input id="password" required />
            </div>

            <label for="remember_me" class="flex items-center gap-2">
                <x-checkbox id="remember_me" name="remember" />
                <span class="text-sm text-gray-600">Mantener la sesión iniciada</span>
            </label>

            <x-button class="w-full py-3 text-base">Iniciar sesión</x-button>
        </form>

        @if (Route::has('register'))
            <p class="mt-8 text-sm text-gray-600">
                ¿Aún no tienes cuenta?
                <a href="{{ route('register') }}" class="font-semibold text-green-700 hover:text-green-800">Crea una</a>
            </p>
        @endif
    </x-authentication-card>
</x-guest-layout>
