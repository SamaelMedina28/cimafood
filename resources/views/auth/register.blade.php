<x-guest-layout>
    @php
        $role = old('is_vendor', request('rol') === 'vendedor' ? '1' : '0');
        $roles = [
            '0' => ['title' => 'Quiero pedir', 'text' => 'Compra en los negocios del campus.', 'icon' => 'bag'],
            '1' => ['title' => 'Tengo un negocio', 'text' => 'Publica tu menú y recibe pedidos.', 'icon' => 'store'],
        ];
    @endphp

    <x-authentication-card title="Crea tu cuenta" description="Te toma un minuto." image="example_images/products/burritos/img3.jpg">
        <x-validation-errors class="mb-6" />

        <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ role: '{{ $role }}' }">
            @csrf

            <fieldset>
                <legend class="text-sm font-medium text-gray-900">¿Cómo vas a usar CimaFood?</legend>
                <div class="mt-2 grid grid-cols-2 gap-3">
                    @foreach ($roles as $value => $option)
                        @php $selected = (string) $role === (string) $value; @endphp
                        <label @class([
                                'relative cursor-pointer rounded-2xl border p-4 transition-colors focus-within:ring-2 focus-within:ring-green-600',
                                'border-green-600 bg-green-50' => $selected,
                                'border-gray-200 hover:border-gray-300' => !$selected,
                            ])
                            x-bind:class="{ 'border-green-600 bg-green-50': role === '{{ $value }}', 'border-gray-200 hover:border-gray-300': role !== '{{ $value }}' }">
                            <input type="radio" name="is_vendor" value="{{ $value }}" x-model="role" @checked($selected) class="sr-only">
                            <x-icon :name="$option['icon']" class="size-6 text-green-700" />
                            <span class="mt-3 block font-semibold text-gray-900">{{ $option['title'] }}</span>
                            <span class="mt-0.5 block text-sm text-gray-500">{{ $option['text'] }}</span>
                            <span x-show="role === '{{ $value }}'" @unless ($selected) style="display: none;" @endunless
                                class="absolute top-3 right-3 flex size-5 items-center justify-center rounded-full bg-green-600 text-white">
                                <x-icon name="check" class="size-3.5" />
                            </span>
                        </label>
                    @endforeach
                </div>
            </fieldset>

            <div>
                <x-label for="name" value="Nombre" />
                <x-input id="name" class="mt-1.5 block w-full" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <x-label for="email" value="Correo electrónico" />
                    <x-input id="email" class="mt-1.5 block w-full" type="email" name="email" :value="old('email')"
                        required autocomplete="username" placeholder="tu@correo.com" />
                </div>
                <div>
                    <x-label for="phone" value="Teléfono" />
                    <x-input id="phone" class="mt-1.5 block w-full" type="tel" name="phone" :value="old('phone')"
                        required autocomplete="tel" placeholder="686 123 4567" />
                </div>
            </div>

            <div>
                <x-label for="password" value="Contraseña" />
                <x-password-input id="password" required autocomplete="new-password" />
                <p class="mt-1.5 text-xs text-gray-500">Mínimo 8 caracteres.</p>
            </div>

            <div>
                <x-label for="password_confirmation" value="Repite la contraseña" />
                <x-password-input id="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <label for="terms" class="flex items-start gap-2 text-sm text-gray-600">
                    <x-checkbox name="terms" id="terms" required class="mt-0.5" />
                    <span>
                        Acepto los <a target="_blank" href="{{ route('terms.show') }}" class="font-medium text-gray-900 underline">términos del servicio</a>
                        y la <a target="_blank" href="{{ route('policy.show') }}" class="font-medium text-gray-900 underline">política de privacidad</a>.
                    </span>
                </label>
            @endif

            <x-button class="w-full py-3 text-base">Crear cuenta</x-button>
        </form>

        <p class="mt-8 text-sm text-gray-600">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-green-700 hover:text-green-800">Inicia sesión</a>
        </p>
    </x-authentication-card>
</x-guest-layout>
