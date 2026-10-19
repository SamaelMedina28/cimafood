@props(['business'])

@php
    $isOpen = $business->isOpen();
    // Usa los agregados precargados (withAvg/withCount) para no consultar por cada tarjeta
    $preloaded = array_key_exists('reviews_count', $business->getAttributes());
    $rating = $preloaded ? $business->reviews_avg_rating : $business->averageRating();
    $reviewsCount = $preloaded ? $business->reviews_count : $business->totalReviews();
@endphp

<a href="{{ route('store.business', $business->id) }}" wire:navigate
    {{ $attributes->class('group block rounded-2xl focus:outline-none focus-visible:ring-2 focus-visible:ring-green-600 focus-visible:ring-offset-4') }}>
    <div class="relative aspect-[16/9] overflow-hidden rounded-2xl bg-gray-100">
        @if ($business->banner_url)
            <img src="{{ $business->banner_url }}" alt="" loading="lazy"
                @class(['size-full object-cover transition-transform duration-500 group-hover:scale-[1.03]', 'grayscale-[60%]' => !$isOpen])>
        @else
            <div class="size-full bg-green-50"></div>
        @endif

        @unless ($isOpen)
            <span class="absolute top-3 left-3 rounded-full bg-white px-2.5 py-1 text-xs font-medium text-gray-700 shadow-sm">
                Abre a las {{ substr($business->open_time, 0, 5) }}
            </span>
        @endunless
    </div>

    <div class="mt-3 flex items-start gap-3">
        <div class="size-11 shrink-0 overflow-hidden rounded-full bg-green-50 ring-1 ring-gray-200">
            @if ($business->logo_url)
                <img src="{{ $business->logo_url }}" alt="" loading="lazy" class="size-full object-cover">
            @else
                <span class="flex size-full items-center justify-center font-display font-bold text-green-700">
                    {{ mb_strtoupper(mb_substr($business->name, 0, 1)) }}
                </span>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <h3 class="font-semibold text-gray-900 truncate group-hover:text-green-700 transition-colors">
                {{ $business->name }}</h3>
            <div class="mt-0.5 flex items-center gap-3 text-sm text-gray-500">
                <span class="inline-flex items-center gap-1">
                    <x-icon name="star" class="size-4 text-amber-400" />
                    @if ($rating)
                        <span class="font-medium text-gray-700">{{ number_format($rating, 1) }}</span>
                        <span>({{ $reviewsCount }})</span>
                    @else
                        <span>Sin reseñas</span>
                    @endif
                </span>
                @if ($isOpen)
                    <span class="inline-flex items-center gap-1.5 text-green-700">
                        <span class="size-1.5 rounded-full bg-green-600"></span>
                        Abierto
                    </span>
                @endif
            </div>
        </div>
    </div>
</a>
