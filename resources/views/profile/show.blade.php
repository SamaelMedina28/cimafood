{{-- Los clientes ven su perfil dentro de la tienda; los vendedores, dentro del panel --}}
<x-dynamic-component :component="Auth::user()->is_vendor ? 'app-layout' : 'client-layout'">
    <x-slot name="header">
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Tu cuenta</h1>
        <p class="mt-1 text-gray-500">Tus datos, tu contraseña y la seguridad de tu cuenta.</p>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 {{ Auth::user()->is_vendor ? 'lg:px-10' : 'lg:px-8' }} pt-8 pb-16 space-y-10">
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')
        @endif

        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <x-section-border />
            @livewire('profile.update-password-form')
        @endif

        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <x-section-border />
            @livewire('profile.two-factor-authentication-form')
        @endif

        <x-section-border />
        @livewire('profile.logout-other-browser-sessions-form')

        @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
            <x-section-border />
            @livewire('profile.delete-user-form')
        @endif
    </div>
</x-dynamic-component>
