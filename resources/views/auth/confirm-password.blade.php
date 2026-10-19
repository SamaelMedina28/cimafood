<x-guest-layout>
    <x-authentication-card title="Confirma tu contraseña"
        description="Por seguridad, escribe tu contraseña antes de continuar.">
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <div>
                <x-label for="password" value="Contraseña" />
                <x-password-input id="password" required autofocus autocomplete="current-password" />
            </div>

            <x-button class="w-full py-3 text-base">Continuar</x-button>
        </form>
    </x-authentication-card>
</x-guest-layout>
