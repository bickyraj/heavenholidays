@extends('layouts.front')

@section('content')
    {{-- Slider --}}
    @include('front.elements.banner')

    {{-- About --}}
    <div class="py-20 about" x-data="{ expanded: false }">
        <div class="container mb-4">
            <div class="grid gap-10 xl:grid-cols-2">
                <div class="lg:p-10 slide-right">
                    <div id="overview-content-block" class="relative mx-auto prose text-center showMore" x-show="expanded" x-collapse.min.480px>
                        <p class="mb-2 text-2xl text-center font-handwriting text-primary">About us</p>

                        <h1 class="relative px-10 mb-8 text-3xl font-bold text-center text-gray-600 uppercase lg:text-5xl font-display">
                            {{ Setting::get('homePage')['welcome']['title'] ?? '' }}
                            <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                            <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        </h1>
                        <?= Setting::get('homePage')['welcome']['content'] ?? '' ?>
                        <div class="h-4"></div>
                    </div>
                    <div class="container flex justify-center">
                        <button id="show-more-btn" class="px-2 py-1 btn__show-more bg-light" data-status="false" x-text="expanded ? 'Show less' : 'Show more'" @click="expanded=!expanded">
                        </button>
                    </div>
                </div>
                <div class="slide-left">
                    <div class="flex justify-center">
                        <a data-fancybox class="relative block lg:p-10" href="https://www.youtube.com/watch?v=d9elHMRK294&amp;autoplay=1&amp;rel=0&amp;controls=1&amp;showinfo=0">
                            <img src="https://www.havenholidaysnepal.com/storage/trip-galleries/28/f3f438ace8827cea8848f5a68ba29be9.jpg" alt="" class="rounded-3xl">
                            <div class="absolute -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                <div class="w-24 h-24 bg-white rounded-full animate-ping "></div>
                            </div>
                            <div class="absolute -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2">
                                <svg class="w-24 h-24 text-primary" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                        d="M2 10a8 8 0 1116 0 8 8 0 01-16 0zm6.39-2.908a.75.75 0 01.766.027l3.5 2.25a.75.75 0 010 1.262l-3.5 2.25A.75.75 0 018 12.25v-4.5a.75.75 0 01.39-.658z"></path>
                                </svg>
                            </div>
                        </a>
                    </div>
                    <div class="grid grid-cols-3 gap-10 lg:p-10">
                        <div>
                            <svg class="flex-shrink-0 w-16 h-16 p-4 mr-4 rounded-xl text-primary bg-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path
                                    d="M173.8 5.5c11-7.3 25.4-7.3 36.4 0L228 17.2c6 3.9 13 5.8 20.1 5.4l21.3-1.3c13.2-.8 25.6 6.4 31.5 18.2l9.6 19.1c3.2 6.4 8.4 11.5 14.7 14.7L344.5 83c11.8 5.9 19 18.3 18.2 31.5l-1.3 21.3c-.4 7.1 1.5 14.2 5.4 20.1l11.8 17.8c7.3 11 7.3 25.4 0 36.4L366.8 228c-3.9 6-5.8 13-5.4 20.1l1.3 21.3c.8 13.2-6.4 25.6-18.2 31.5l-19.1 9.6c-6.4 3.2-11.5 8.4-14.7 14.7L301 344.5c-5.9 11.8-18.3 19-31.5 18.2l-21.3-1.3c-7.1-.4-14.2 1.5-20.1 5.4l-17.8 11.8c-11 7.3-25.4 7.3-36.4 0L156 366.8c-6-3.9-13-5.8-20.1-5.4l-21.3 1.3c-13.2 .8-25.6-6.4-31.5-18.2l-9.6-19.1c-3.2-6.4-8.4-11.5-14.7-14.7L39.5 301c-11.8-5.9-19-18.3-18.2-31.5l1.3-21.3c.4-7.1-1.5-14.2-5.4-20.1L5.5 210.2c-7.3-11-7.3-25.4 0-36.4L17.2 156c3.9-6 5.8-13 5.4-20.1l-1.3-21.3c-.8-13.2 6.4-25.6 18.2-31.5l19.1-9.6C65 70.2 70.2 65 73.4 58.6L83 39.5c5.9-11.8 18.3-19 31.5-18.2l21.3 1.3c7.1 .4 14.2-1.5 20.1-5.4L173.8 5.5zM272 192c0-44.2-35.8-80-80-80s-80 35.8-80 80s35.8 80 80 80s80-35.8 80-80zM1.3 441.8L44.4 339.3c.2 .1 .3 .2 .4 .4l9.6 19.1c11.7 23.2 36 37.3 62 35.8l21.3-1.3c.2 0 .5 0 .7 .2l17.8 11.8c5.1 3.3 10.5 5.9 16.1 7.7l-37.6 89.3c-2.3 5.5-7.4 9.2-13.3 9.7s-11.6-2.2-14.8-7.2L74.4 455.5l-56.1 8.3c-5.7 .8-11.4-1.5-15-6s-4.3-10.7-2.1-16zm248 60.4L211.7 413c5.6-1.8 11-4.3 16.1-7.7l17.8-11.8c.2-.1 .4-.2 .7-.2l21.3 1.3c26 1.5 50.3-12.6 62-35.8l9.6-19.1c.1-.2 .2-.3 .4-.4l43.2 102.5c2.2 5.3 1.4 11.4-2.1 16s-9.3 6.9-15 6l-56.1-8.3-32.2 49.2c-3.2 5-8.9 7.7-14.8 7.2s-11-4.3-13.3-9.7z" />
                            </svg>
                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-gray-500 font-display">Local expert guides</h3>
                            </div>
                        </div>
                        <div>

                            <svg class="flex-shrink-0 w-16 h-16 p-4 mr-4 rounded-xl text-primary bg-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M0 416c0-17.7 14.3-32 32-32l54.7 0c12.3-28.3 40.5-48 73.3-48s61 19.7 73.3 48L480 384c17.7 0 32 14.3 32 32s-14.3 32-32 32l-246.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 448c-17.7 0-32-14.3-32-32zm192 0c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zM384 256c0-17.7-14.3-32-32-32s-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32zm-32-80c32.8 0 61 19.7 73.3 48l54.7 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-54.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l246.7 0c12.3-28.3 40.5-48 73.3-48zM192 64c-17.7 0-32 14.3-32 32s14.3 32 32 32s32-14.3 32-32s-14.3-32-32-32zm73.3 0L480 64c17.7 0 32 14.3 32 32s-14.3 32-32 32l-214.7 0c-12.3 28.3-40.5 48-73.3 48s-61-19.7-73.3-48L32 128C14.3 128 0 113.7 0 96S14.3 64 32 64l86.7 0C131 35.7 159.2 16 192 16s61 19.7 73.3 48z" />
                            </svg>
                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-gray-500 font-display">Customized Tours</h3>
                            </div>
                        </div>
                        <div>
                            <svg class="flex-shrink-0 w-16 h-16 p-4 mr-4 rounded-xl text-primary bg-light" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M269.4 2.9C265.2 1 260.7 0 256 0s-9.2 1-13.4 2.9L54.3 82.8c-22 9.3-38.4 31-38.3 57.2c.5 99.2 41.3 280.7 213.6 363.2c16.7 8 36.1 8 52.8 0C454.7 420.7 495.5 239.2 496 140c.1-26.2-16.3-47.9-38.3-57.2L269.4 2.9zM144 221.3c0-33.8 27.4-61.3 61.3-61.3c16.2 0 31.8 6.5 43.3 17.9l7.4 7.4 7.4-7.4c11.5-11.5 27.1-17.9 43.3-17.9c33.8 0 61.3 27.4 61.3 61.3c0 16.2-6.5 31.8-17.9 43.3l-82.7 82.7c-6.2 6.2-16.4 6.2-22.6 0l-82.7-82.7c-11.5-11.5-17.9-27.1-17.9-43.3z" />
                            </svg>
                            <div class="mt-4">
                                <h3 class="text-xl font-bold text-gray-500 font-display">Safety ensured</h3>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>{{-- About --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
    @endpush

    {{-- Popular right now --}}
    <div class="py-20 featured bg-light">
        <div class="container">

            <div class="flex justify-center">
                <div>
                    <p class="mb-2 text-2xl text-center text-primary font-handwriting">The best of what we offer</p>
                    <h2 class="relative px-10 mb-16 text-3xl font-bold text-center text-gray-600 uppercase lg:text-5xl font-display">
                        {{ Setting::get('homePage')['trip_block_2']['title'] ?? '' }}
                        <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    </h2>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 md:gap-8">
                @forelse ($block_2_trips as $block_2_tour)
                    @include('front.elements.tour-card', ['tour' => $block_2_tour])
                @empty
                @endforelse
            </div>
            <div class="text-center">
                <a href="{{ route('front.trips.listing') }}" class="mt-10 btn btn-primary">
                    View all
                </a>
            </div>
        </div>
    </div>
    {{-- Popular right now --}}

    {{-- Destinations --}}
    <div class="relative py-20 destinations">
        <div class="container">
            <p class="mb-2 text-2xl text-center font-handwriting text-primary">Where do you want to go?</p>
            <div class="flex justify-center mb-16">
                <h2 class="relative px-10 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">
                    Destinations
                    <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                </h2>
            </div>
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                @forelse ($destinations as $destination)
                @include('front.elements.destination_card', ['destination' => $destination])
                @empty
                @endforelse
            </div>
        </div>
        <div class="absolute bottom-0 -translate-x-1/2 left-1/2">
            <svg viewbox="0 0 40 6" class="h-12 text-light">
                <path d="M0 6 10 2 14 4 20 0 26 4 30 2 40 6 0 6" fill="currentColor">
            </svg>
        </div>
    </div>{{-- Destinations --}}

    {{-- Activities --}}
    <div class="py-20 activities bg-light">
        <div class="container">
            <div class="items-center justify-between gap-20 mb-4 lg:flex">
                <div>
                    <p class="mb-2 text-2xl font-handwriting text-primary">Choose your activity</p>
                    <div class="flex">
                        <h2 class="relative pr-10 mb-8 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">
                            A wide selection of activities
                            <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        </h2>
                    </div>
                </div>
                <div class="flex gap-10 activities-slider-controls">
                    <button>
                        <svg class="w-6 h-6 text-accent">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowleft') }}" />
                        </svg>
                    </button>
                    <button>
                        <svg class="w-6 h-6 text-accent">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="activities-slider">
                @foreach ($activities as $activity)
                    @include('front.elements.activity-card', ['activity' => $activity])
                @endforeach
            </div>
        </div>
    </div>{{-- Activities --}}

    {{-- Reviews --}}
    <div class="py-20 reviews">
        <div class="container">

            <div class="items-end justify-between mb-8 lg:flex">
                <div>
                    <p class="mb-2 text-2xl text-center font-handwriting text-primary">Messages from satisfied customers</p>
                    <div class="flex">
                        <h2 class="relative pr-10 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">
                            Reviews
                            <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        </h2>
                    </div>

                </div>
                <a href="{{ route('front.reviews.index') }}" class="text-accent">
                    View all
                    <svg class="w-4 h-4">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#chevronright') }}" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-4 lg:grid-cols-2 lg:gap-10">
                @forelse ($reviews as $review)
                    <div class="py-4 review @if ($loop->odd) slide-right @else slide-left @endif">
                        <div class="prose review__content">
                            <h2 class="mb-2 text-2xl font-display text-primary">{{ $review->title }}</h2>
                            <p class="review__body">{{ $review->review }}</p>
                        </div>
                        <div class="flex items-center mt-8">
                            <div class="mr-2">
                                <img src="{{ $review->thumbImageUrl }}" alt="">
                            </div>
                            <div>
                                <div class="font-bold">{{ ucfirst($review->review_name) }}</div>
                                <div class="text-sm text-gray">{{ $review->review_country }}</div>
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>{{-- Reviews --}}

    {{-- Trip slider --}}
    <div class="py-24 text-white bg-primary tour-slider-section">
        <div class="container">
            <p class="mb-2 text-2xl text-white font-handwriting">This doesn't get any better</p>

            <div class="flex">
                <h2 class="relative pr-10 mb-8 text-3xl font-bold uppercase lg:text-5xl font-display">
                    {{ Setting::get('homePage')['trip_block_3']['title'] ?? '' }}
                    <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                </h2>
            </div>

            <div class="flex justify-end gap-4 mb-10 trips-month-slider-controls">
                <button class="p-2 rounded-lg bg-light">
                    <svg class="w-6 h-6 text-accent">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowleft') }}" />
                    </svg>
                </button>
                <button class="p-2 rounded-lg bg-light">
                    <svg class="w-6 h-6 text-accent">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                    </svg>
                </button>
            </div>

            <div class="trips-month-slider">
                @forelse ($block_3_trips as $block3tour)
                    @include('front.elements.tour_card_slider', ['tour' => $block3tour])
                @empty
                @endforelse
            </div>
        </div>
    </div>{{-- Trip slider --}}

    {{-- Blog --}}
    <div class="blog">
        <div class="container py-20">
            <div class="flex">
                <h2 class="relative pr-10 mb-8 text-2xl font-bold uppercase lg:text-3xl font-display text-primary">
                    Blog
                    <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                </h2>
            </div>
            <div class="grid gap-10 lg:grid-cols-2">
                @forelse ($blogs as $blog)
                    <a href="{{ route('front.blogs.show', $blog->slug) }}">
                        <div class="article">
                            <div class="relative">
                                <img data-src="{{ $blog->medium_imageUrl }}" alt="{{ $blog->name }}" class="lazyload" title="{{ $blog->name }}" width="300" height="200">
                                <div class="absolute w-20 h-20 p-4 text-center text-white uppercase left-4 bottom-4 bg-primary">
                                    {{ date_format(date_create($blog->blog_date), 'M') }}<br>
                                    <div class="text-2xl font-bold">{{ date_format(date_create($blog->blog_date), 'd') }}</div>
                                </div>
                            </div>
                            <div class="content">
                                <h2 class="text-2xl font-bold text-gray-600 hover:text-primary">{{ $blog->name }}</h2>

                                <div class="mt-4 prose">
                                    <p>
                                        {{ truncate(strip_tags($blog->description)) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                @endforelse
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('front.blogs.index') }}" class="btn btn-primary">Go to blog
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                    </svg>
                </a>
            </div>
        </div>
    </div>{{-- Blog --}}

    @include('front.elements.search_widget')
@endsection

@push('scripts')
    <script>
        $(function() {
            $("#select-trip-departure-filter").on('change', function(event) {
                event.preventDefault();
                let url = "{!! route('front.trip-departures.filter') !!}";
                let e = $(this);
                let month = e.children("option:selected").val();

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        month: month
                    },
                    async: false,
                    success: function(response) {
                        if (response.data != "") {
                            $("#departure-card-block").html(response.data);
                        } else {
                            $("#departure-card-block").html('No data to show.');
                        }
                    }
                });
            });

            $("#banner-slider>.slide").each(function(i, v) {
                let img = new Image();
                let image_src = $(v).find('img').data('img');
                img.onload = function() {
                    $(v).find('img').attr('src', image_src);
                }
                img.src = image_src;
                if (img.complete) img.onload();
            });

            const activitiesSlider = tns({
                container: '.activities-slider',
                nav: false,
                controlsContainer: '.activities-slider-controls',
                items: 2,
                gutter: 16,
                rewind: true,
                responsive: {
                    768: {
                        items: 3
                    },
                    992: {
                        items: 5
                    }
                }
            })

            const monthSlider = tns({
                container: '.trips-month-slider',
                nav: false,
                controlsContainer: '.trips-month-slider-controls',
                autoplay: true,
                autoplayButtonOutput: false
            });
        });
    </script>
@endpush
