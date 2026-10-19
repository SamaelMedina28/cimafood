<x-guest-layout>
    <x-authentication-card title="Crea una nueva contraseña" description="Elige una que no hayas usado antes.">
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <x-label for="email" value="Correo electrónico" />
                <x-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email', $request->email)"
                    required autocomplete="username" />
            </div>

            <div>
                <x-label for="password" value="Nueva contraseña" />
                <x-password-input id="password" required autofocus autocomplete="new-password" />
                <p class="mt-1.5 text-xs text-gray-500">Mínimo 8 caracteres.</p>
            </div>

            <div>
                <x-label for="password_confirmation" value="Repite la contraseña" />
                <x-password-input id="password_confirmation" required autocomplete="new-password" />
            </div>

            <x-button class="w-full py-3 text-base">Guardar contraseña</x-button>
        </form>
    </x-authentication-card>
</x-guest-layout>
