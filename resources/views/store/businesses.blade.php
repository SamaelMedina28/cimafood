<x-client-layout>
    <x-slot name="header">
        <h1 class="font-display text-3xl sm:text-4xl font-extrabold tracking-tight text-gray-900">Negocios</h1>
        <p class="mt-1 text-gray-500">Todos los negocios del campus que reciben pedidos en CimaFood.</p>
    </x-slot>

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
        @if ($businesses->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-10">
                @foreach ($businesses as $business)
                    <x-store.business-card :business="$business" />
                @endforeach
            </div>

            @if ($businesses->hasPages())
                <div class="mt-12">
                    {{ $businesses->appends(request()->query())->links() }}
                </div>
            @endif
        @else
            <div class="rounded-2xl bg-gray-50 px-6 py-16 text-center">
                <x-icon name="store" class="mx-auto size-10 text-gray-300" />
                <h2 class="mt-4 font-display text-lg font-bold text-gray-900">Aún no hay negocios</h2>
                <p class="mt-1 text-sm text-gray-500">Cuando un negocio se registre aparecerá aquí.</p>
            </div>
        @endif
    </div>
</x-client-layout>
