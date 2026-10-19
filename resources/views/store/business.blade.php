<x-client-layout>
    @php
        $isOpen = $business->isOpen();
        $rating = $business->averageRating();
        $reviewsCount = $business->totalReviews();
    @endphp

    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-16">
        <a href="{{ route('store.businesses') }}" wire:navigate
            class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-900">
            <x-icon name="chevron-left" class="size-4" />
            Negocios
        </a>

        {{-- Portada --}}
        <div class="mt-3 relative aspect-[2/1] sm:aspect-[3/1] overflow-hidden rounded-3xl bg-green-50">
            @if ($business->banner_url)
                <img src="{{ $business->banner_url }}" alt="" class="size-full object-cover">
            @endif
        </div>

        {{-- Info del negocio --}}
        <div class="relative px-1 sm:px-4">
            <div class="-mt-10 sm:-mt-14 size-20 sm:size-28 overflow-hidden rounded-full bg-white ring-4 ring-white shadow-sm">
                @if ($business->logo_url)
                    <img src="{{ $business->logo_url }}" alt="Logo de {{ $business->name }}" class="size-full object-cover">
                @else
                    <span class="flex size-full items-center justify-center bg-green-50 font-display text-3xl font-bold text-green-700">
                        {{ mb_strtoupper(mb_substr($business->name, 0, 1)) }}
                    </span>
                @endif
            </div>

            <div class="mt-4 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-2xl">
                    <h1 class="font-display text-3xl sm:text-5xl font-extrabold tracking-tight text-gray-900 leading-none">
                        {{ $business->name }}</h1>
                    @if ($business->description)
                        <p class="mt-3 text-gray-600">{{ $business->description }}</p>
                    @endif

                    <div class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                        <span @class(['inline-flex items-center gap-1.5 font-medium', 'text-green-700' => $isOpen, 'text-gray-600' => !$isOpen])>
                            <span @class(['size-2 rounded-full', 'bg-green-600' => $isOpen, 'bg-gray-400' => !$isOpen])></span>
                            @if ($isOpen)
                                Abierto, cierra a las {{ substr($business->close_time, 0, 5) }}
                            @else
                                Cerrado, abre a las {{ substr($business->open_time, 0, 5) }}
                            @endif
                        </span>
                        <button type="button" @click="window.dispatchEvent(new CustomEvent('open-reviews-modal'))"
                            class="inline-flex items-center gap-1 text-gray-600 hover:text-gray-900">
                            <x-icon name="star" class="size-4 text-amber-400" />
                            @if ($rating)
                                <span class="font-semibold text-gray-900">{{ number_format($rating, 1) }}</span>
                                <span class="underline underline-offset-2 decoration-gray-300">{{ $reviewsCount }} {{ $reviewsCount === 1 ? 'reseña' : 'reseñas' }}</span>
                            @else
                                <span class="underline underline-offset-2 decoration-gray-300">Sin reseñas</span>
                            @endif
                        </button>
                    </div>
                </div>

                @if ($hasReviewed)
                    <p class="inline-flex items-center gap-1.5 text-sm text-gray-500">
                        <x-icon name="check" class="size-4 text-green-600" />
                        Ya dejaste tu reseña
                    </p>
                @elseif ($canReview)
                    <button type="button" @click="window.dispatchEvent(new CustomEvent('open-review-modal'))"
                        class="inline-flex shrink-0 items-center gap-2 self-start lg:self-auto rounded-full border border-gray-200 px-5 py-2.5 text-sm font-semibold text-gray-900 hover:border-gray-300 hover:bg-gray-50 transition-colors">
                        <x-icon name="pencil" class="size-4" />
                        Escribir reseña
                    </button>
                @else
                    <p class="max-w-56 text-sm text-gray-500 lg:text-right">Podrás reseñar este negocio cuando recibas un pedido suyo.</p>
                @endif
            </div>
        </div>

        {{-- Menú --}}
        <section class="mt-12" aria-labelledby="menu-title">
            <div class="flex items-baseline justify-between border-b border-gray-200 pb-3">
                <h2 id="menu-title" class="font-display text-2xl font-bold text-gray-900">Menú</h2>
                <span class="text-sm text-gray-500">{{ $business->products->count() }} productos</span>
            </div>

            @if ($business->products->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-10">
                    @foreach ($business->products as $product)
                        <x-store.product-card :product="$product" :business="$business" variant="row"
                            :favorite="in_array($product->id, $favorites)" class="border-b border-gray-100" />
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center">
                    <x-icon name="photo" class="mx-auto size-10 text-gray-300" />
                    <h3 class="mt-4 font-display text-lg font-bold text-gray-900">Este negocio aún no tiene productos</h3>
                    <p class="mt-1 text-sm text-gray-500">Vuelve pronto para ver su menú.</p>
                </div>
            @endif
        </section>
    </div>

    {{-- Modal para dejar reseña --}}
    <div x-data="{
        showModal: false,
        rating: 0,
        hover: 0,
        comment: '',
        loading: false,
        error: '',
        init() {
            window.addEventListener('open-review-modal', () => {
                this.showModal = true;
            });
        },
        submitReview() {
            if (this.rating === 0) {
                this.error = 'Elige una calificación de 1 a 5 estrellas.';
                return;
            }
            this.error = '';
            this.loading = true;
            fetch('{{ route('store.reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        business_id: {{ $business->id }},
                        rating: this.rating,
                        comment: this.comment
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.errors) {
                        this.error = Object.values(data.errors).flat()[0];
                    } else if (data.success) {
                        this.showModal = false;
                        this.rating = 0;
                        this.comment = '';
                        window.location.reload();
                    } else {
                        this.error = 'No se pudo publicar la reseña. Intenta de nuevo.';
                    }
                })
                .catch(err => {
                    console.error(err);
                    this.error = 'No se pudo publicar la reseña. Revisa tu conexión e intenta de nuevo.';
                })
                .finally(() => {
                    this.loading = false;
                });
        }
    }" x-init="init()" x-show="showModal" x-transition.opacity @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-gray-900/40 sm:p-4" style="display: none;"
        role="dialog" aria-modal="true" aria-labelledby="review-title">
        <div @click.outside="showModal = false" class="w-full sm:max-w-md rounded-t-3xl sm:rounded-3xl bg-white p-6 shadow-2xl">
            <div class="flex items-start justify-between">
                <div>
                    <h3 id="review-title" class="font-display text-xl font-bold text-gray-900">Escribir reseña</h3>
                    <p class="mt-0.5 text-sm text-gray-500">{{ $business->name }}</p>
                </div>
                <button type="button" @click="showModal = false" aria-label="Cerrar"
                    class="-mr-2 -mt-1 p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                    <x-icon name="x" />
                </button>
            </div>

            <fieldset class="mt-6">
                <legend class="text-sm font-medium text-gray-900">Calificación</legend>
                <div class="mt-2 flex gap-1" @mouseleave="hover = 0">
                    <template x-for="i in 5">
                        <button type="button" @click="rating = i" @mouseenter="hover = i"
                            :aria-label="i + (i === 1 ? ' estrella' : ' estrellas')"
                            class="p-0.5 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 rounded"
                            :class="(hover || rating) >= i ? 'text-amber-400' : 'text-gray-200'">
                            <x-icon name="star" class="size-9" />
                        </button>
                    </template>
                </div>
            </fieldset>

            <div class="mt-5">
                <label for="review-comment" class="text-sm font-medium text-gray-900">Comentario <span
                        class="font-normal text-gray-500">(opcional)</span></label>
                <textarea id="review-comment" x-model="comment" rows="4" maxlength="1000"
                    class="mt-2 w-full rounded-xl border-gray-200 text-sm focus:border-green-600 focus:ring-green-600 resize-none"
                    placeholder="¿Qué pediste y qué tal estuvo?"></textarea>
            </div>

            <p x-show="error" x-text="error" class="mt-3 text-sm text-red-600"></p>

            <div class="mt-6 flex gap-3">
                <button type="button" @click="showModal = false"
                    class="flex-1 rounded-full border border-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="button" @click="submitReview()" :disabled="loading"
                    class="flex-1 rounded-full bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition-colors disabled:opacity-60 disabled:cursor-not-allowed">
                    <span x-show="!loading">Publicar reseña</span>
                    <span x-show="loading">Publicando</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Modal para ver todas las reseñas --}}
    <div x-data="{
        showModal: false,
        init() {
            window.addEventListener('open-reviews-modal', () => {
                this.showModal = true;
            });
        }
    }" x-init="init()" x-show="showModal" x-transition.opacity @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center bg-gray-900/40 sm:p-4" style="display: none;"
        role="dialog" aria-modal="true" aria-labelledby="reviews-title">
        <div @click.outside="showModal = false"
            class="w-full sm:max-w-lg max-h-[85vh] flex flex-col rounded-t-3xl sm:rounded-3xl bg-white shadow-2xl">
            <div class="flex items-start justify-between px-6 pt-6 pb-4 border-b border-gray-100">
                <div>
                    <h3 id="reviews-title" class="font-display text-xl font-bold text-gray-900">Reseñas</h3>
                    @if ($rating)
                        <p class="mt-1 flex items-center gap-1 text-sm text-gray-500">
                            <x-icon name="star" class="size-4 text-amber-400" />
                            <span class="font-semibold text-gray-900">{{ number_format($rating, 1) }}</span>
                            de 5, {{ $reviewsCount }} {{ $reviewsCount === 1 ? 'reseña' : 'reseñas' }}
                        </p>
                    @endif
                </div>
                <button type="button" @click="showModal = false" aria-label="Cerrar"
                    class="-mr-2 -mt-1 p-2 rounded-full text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                    <x-icon name="x" />
                </button>
            </div>

            <div class="flex-1 overflow-y-auto px-6">
                @forelse ($business->reviews->sortByDesc('created_at') as $review)
                    <article class="py-5 border-b border-gray-100 last:border-0">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <span class="flex size-9 items-center justify-center rounded-full bg-green-50 font-display font-bold text-green-700">
                                    {{ mb_strtoupper(mb_substr($review->user->name ?? 'A', 0, 1)) }}
                                </span>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $review->user->name ?? 'Anónimo' }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->translatedFormat('j M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex" aria-label="{{ $review->rating }} de 5 estrellas">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-icon name="star" :class="$i <= $review->rating ? 'size-4 text-amber-400' : 'size-4 text-gray-200'" />
                                @endfor
                            </div>
                        </div>
                        @if ($review->comment)
                            <p class="mt-3 text-sm text-gray-700">{{ $review->comment }}</p>
                        @endif
                    </article>
                @empty
                    <div class="py-12 text-center">
                        <x-icon name="star" class="mx-auto size-10 text-gray-200" />
                        <p class="mt-3 font-medium text-gray-900">Aún no hay reseñas</p>
                        <p class="mt-1 text-sm text-gray-500">Si ya pediste aquí, cuéntale a los demás qué tal estuvo.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-client-layout>
