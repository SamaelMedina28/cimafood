<x-guest-layout>
    <x-authentication-card title="Confirma tu correo"
        description="Te enviamos un enlace de verificación. Ábrelo para activar tu cuenta.">
        @if (session('status') == 'verification-link-sent')
            <x-auth-status class="mb-6">Te enviamos un nuevo enlace al correo de tu cuenta.</x-auth-status>
        @endif

        <div class="flex items-center gap-3 rounded-2xl bg-gray-50 p-4">
            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white text-green-700 ring-1 ring-gray-200">
                <x-icon name="mail" />
            </span>
            <p class="text-sm text-gray-600">Revisa también la carpeta de spam si no lo ves en unos minutos.</p>
        </div>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
            @csrf
            <x-button class="w-full py-3 text-base">Reenviar enlace</x-button>
        </form>

        <div class="mt-8 flex items-center gap-4 text-sm">
            <a href="{{ route('profile.show') }}" class="font-semibold text-green-700 hover:text-green-800">Cambiar mi correo</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="font-semibold text-gray-600 hover:text-gray-900">Cerrar sesión</button>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
