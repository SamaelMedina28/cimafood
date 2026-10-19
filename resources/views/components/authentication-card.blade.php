@props([
    'title' => null,
    'description' => null,
    // Foto del panel derecho (escritorio)
    'image' => 'example_images/products/tacos/img1.jpg',
])

<div class="min-h-screen bg-white lg:grid lg:grid-cols-2">
    {{-- Formulario --}}
    <div class="flex min-h-screen flex-col px-6 py-6 sm:px-12 lg:px-16">
        <a href="{{ url('/') }}" class="flex w-fit items-center gap-2">
            <img src="{{ asset('Logo-uabc.png') }}" alt="" class="size-9 object-contain">
            <span class="font-display text-xl font-extrabold tracking-tight text-gray-900">Cima<span class="text-green-600">Food</span></span>
        </a>

        <div class="flex flex-1 items-center">
            <div class="mx-auto w-full max-w-sm py-12">
                @if ($title)
                    <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">{{ $title }}</h1>
                @endif
                @if ($description)
                    <p class="mt-2 text-gray-600">{{ $description }}</p>
                @endif

                <div @class(['mt-8' => $title || $description])>
                    {{ $slot }}
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-400">CimaFood, comida de los negocios de la Universidad Autónoma de Baja California.</p>
    </div>

    {{-- Foto (escritorio) --}}
    <div class="relative hidden p-4 lg:block" aria-hidden="true">
        <div class="relative h-full overflow-hidden rounded-3xl bg-gray-100">
            <img src="{{ asset($image) }}" alt="" class="absolute inset-0 size-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
            <p class="absolute inset-x-10 bottom-10 max-w-md font-display text-4xl font-extrabold leading-tight text-white">
                Tacos, burritos, sushi y más de los negocios del campus, en un solo carrito.
            </p>
        </div>
    </div>
</div>
