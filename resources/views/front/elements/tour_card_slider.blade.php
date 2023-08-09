<div class="relative">
    <div class="grid gap-10 lg:grid-cols-2">
        <div>
            <img class="object-cover w-full lazyload aspect-[4/3] rounded-xl" data-src="{{ $tour->imageUrl }}" alt="{{ $tour->name }}" width="400" height="200">
        </div>
        <div>
            <h2 class="mb-16 text-3xl font-bold font-display">
                {{ $tour->name }}
            </h2>
            <p> {{ truncate(strip_tags($tour->trip_info['overview'])) }} </p>

            <div class="mt-4 flex gap-2 mb-4 wrap">
                <div class="flex gap-4 px-4 py-2 rounded-lg bg-primary-d">
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#calendar') }}"></use>
                    </svg>
                    <div>
                        <div class="upper bold fs-xs">Duration</div>
                        <span class="fs-lg bold"> <?= $tour->duration ?> </span> days
                    </div>
                </div>
                <div class="flex gap-4 px-4 py-2 rounded-lg bg-primary-d">
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#emojihappy') }}"></use>
                    </svg>
                    <div>
                        <div class="upper bold fs-xs">Difficulty</div>
                        {{ $tour->difficulty_grade_value }}
                    </div>
                </div>
            </div>

            <div class="price">
                <div>
                    <span class="text-sm">
                        from
                    </span>
                    <s class="font-bold text-accent">
                        USD {{ number_format($tour->cost, 2) }}
                    </s>
                </div>
                <div class="font-display">
                    <span>USD</span>
                    @php
                        $price_arr = explode('.', number_format($tour->offer_price, 2));
                    @endphp
                    <span class="text-4xl">{{ $price_arr[0] }}</span>
                    <span>.{{ $price_arr[1] }}</span>
                </div>
            </div>

            <div class="mt-10">
                <a href="{{ route('front.trips.show', $tour->slug) }}" class="btn btn-accent">
                    Explore
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}"></use>
                    </svg>
                </a>
                {{-- <a href="tour-details.php" class="btn btn-gray">
                    Book Now
                </a> --}}
            </div>
        </div>
    </div>
</div>
