<x-guest-layout>
    <x-authentication-card title="Verificación en dos pasos">
        <div x-data="{ recovery: false }">
            <p class="-mt-6 mb-8 text-gray-600" x-show="! recovery">
                Escribe el código de 6 dígitos que muestra tu aplicación de autenticación.
            </p>
            <p class="-mt-6 mb-8 text-gray-600" x-cloak x-show="recovery">
                Escribe uno de tus códigos de recuperación de emergencia.
            </p>

            <x-validation-errors class="mb-6" />

            <form method="POST" action="{{ route('two-factor.login') }}" class="space-y-5">
                @csrf

                <div x-show="! recovery">
                    <x-label for="code" value="Código" />
                    <x-input id="code" class="mt-1.5 block w-full text-center text-2xl tracking-[0.5em] tabular-nums" type="text"
                        inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" maxlength="6" />
                </div>

                <div x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="Código de recuperación" />
                    <x-input id="recovery_code" class="mt-1.5 block w-full" type="text" name="recovery_code"
                        x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <x-button class="w-full py-3 text-base">Verificar</x-button>
            </form>

            <button type="button" class="mt-8 text-sm font-semibold text-green-700 hover:text-green-800"
                x-show="! recovery" x-on:click="recovery = true; $nextTick(() => { $refs.recovery_code.focus() })">
                Usar un código de recuperación
            </button>
            <button type="button" class="mt-8 text-sm font-semibold text-green-700 hover:text-green-800"
                x-cloak x-show="recovery" x-on:click="recovery = false; $nextTick(() => { $refs.code.focus() })">
                Usar el código de la aplicación
            </button>
        </div>
    </x-authentication-card>
</x-guest-layout>
