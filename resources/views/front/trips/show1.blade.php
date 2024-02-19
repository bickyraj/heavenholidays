<?php
$mapImageUrl = $trip->mapImageUrl;
if (session()->has('success_message')) {
    $session_success_message = session('success_message');
    session()->forget('success_message');
}

if (session()->has('error_message')) {
    $session_error_message = session('error_message');
    session()->forget('error_message');
}
?>
@extends('layouts.front_inner')
@section('meta_og_title'){!! $trip->trip_seo->meta_title ?? '' !!}@stop
@section('meta_description'){!! $trip->trip_seo->meta_description ?? '' !!}@stop
@section('meta_keywords'){!! $trip->trip_seo->meta_keywords ?? '' !!}@stop
@section('meta_og_url'){!! $trip->trip_seo->canonical_url ?? '' !!}@stop
@section('meta_og_description'){!! $trip->trip_seo->meta_description ?? '' !!}@stop
@section('meta_og_image'){!! $trip->trip_seo->ogImageUrl ?? '' !!}@stop
    @push('styles')
        <script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css">
        <style>
            .embed-container {
                position: relative;
                padding-bottom: 56.25%;
                height: 0;
                overflow: hidden;
                max-width: 100%;
            }

            .embed-container iframe,
            .embed-container object,
            .embed-container embed {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
            }
        </style>
        <style type="text/css">
            .modal {
                z-index: 99999 !important;
            }

            .map-image-modal {
                cursor: zoom-in;
                object-fit: cover;
                /*width: 200px;*/
            }

            .trip-faq-description ul li {
                list-style-type: inherit !important;
            }

            .modal-body {
                /* 100% = dialog height, 120px = header + footer */
                /*height: 70vh;*/
                /*overflow-y: scroll;*/
            }
        </style>
    @endpush
@section('content')

    <!-- Hero -->
    <section class="relative hero">
        <div id="hero-slider" class="hero-slider">
            @if (iterator_count($trip->trip_galleries))
                @foreach ($trip->trip_galleries as $gallery)
                    <div class="slide">
                        <img src="{{ $gallery->imageUrl }}" class="block" alt="">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="absolute w-full overlay">
            <div class="container flex flex-wrap items-end justify-between">
                <div>
                    <h1 class="mb-2 text-4xl font-bold text-white hero-title">
                        <span>{{ $trip->name }}</span>
                    </h1>

                    <div class="breadcrumb-wrapper none md:block">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb fs-sm wrap">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('front.trips.listing') }}">Trips</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $trip->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="ratings-wrapper none lg:block">
                    <div class="px-6 py-4 text-white ratings d-flex align-items-center bg-primary text-secondary">
                        <div>
                            @for ($i = 0; $i < $trip->rating; $i++)
                                <svg class="w-4 h-4 text-accent">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#star" />
                                </svg>
                            @endfor

                            @for ($i = 0; $i < 5 - $trip->rating; $i++)
                                <svg class="w-4 h-4 text-accent" viewbox="0 0 20 20" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-width="1.5"
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                        </div>
                        <div class="text-xs">from {{ $trip->reviews_count }} reviews</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute hero-slider-controls none md:block">
            <div class="container flex">
                <button>
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowleft') }}" />
                    </svg>
                </button>
                <button>
                    <svg class="w-6 h-6">
                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                    </svg>
                </button>
            </div>
        </div>

    </section>

    <section class="bg-gray">
        <!-- Sticky Nav -->
        <div class="text-white tdb bg-primary sticky-top" style="top:5rem;z-index:98">
            <div class="container flex items-center justify-center">
                <nav class="flex items-center justify-center tour-details-tabs" id="secondnav">
                    <ul class="flex flex-wrap py-1 nav">
                        <li class="mr-2">
                            <a href="#overview" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viewgrid" />
                                </svg>
                                <span class="none md:block">Overview</span>
                            </a>
                        </li>
                        @if (!$trip->trip_itineraries->isEmpty())
                            <li class="mr-2">
                                <a href="#itinerary" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#clock" />
                                    </svg>
                                    <span class="none md:block">Itinerary</span></a>
                            </li>
                        @endif

                        @if ($trip->trip_include_exclude)
                            <li class="mr-2">
                                <a href="#inclusions" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#archive" />
                                    </svg>
                                    <span class="none md:block">Inclusions</span>
                                </a>
                            </li>
                        @endif

                        @if (!$trip->trip_departures->isEmpty())
                            <li class="mr-2">
                                <a href="#date-price" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendar" />
                                    </svg>
                                    <span class="none md:block">Date & Price</span></a>
                            </li>
                        @endif

                        <li class="mr-2">
                            <a href="#reviews" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#chat" />
                                </svg>
                                <span class="none md:block">Review</span></a>
                        </li>

                        @if (!$trip->trip_faqs->isEmpty())
                            <li class="mr-2">
                                <a href="#faqs" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#questionmarkcircle" />
                                    </svg>
                                    <span class="none md:block">FAQs</span></a>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
            <div id="tourDetailsBarIO"></div>
        </div><!-- Sticky Nav -->

        <div class="container pb-20 mt-2">

            <div class="grid gap-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-10">

                <div class="relative tour-details lg:col-span-2 xl:col-span-3">

                    <div class="lg:none">
                        @include('front.elements.price_card')
                    </div>

                    <div id="overview" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                        <div>

                            <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendarduration" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Duration
                                        </div>
                                        <div>
                                            {{ $trip->duration }} days
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#maxelevation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Max. Elevation
                                        </div>
                                        <div>
                                            {{ $trip->max_altitude }}m
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#groupsize" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Group size
                                        </div>
                                        <div>
                                            {{ $trip->group_size }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#level" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Level
                                        </div>
                                        <div>
                                            {{ $trip->difficulty_grade_value }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#transportation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Transportation
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->transportation ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#bestseason" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Best Season
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->best_season ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#accomodation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Accomodation
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->accomodation ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#meals" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Meals
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->meals ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#startsat" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Starts at
                                        </div>
                                        <div>
                                            {{ $trip->starting_point }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex table-item aic">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#endsat" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Ends at
                                        </div>
                                        <div>
                                            {{ $trip->ending_point }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic lg:col-span-2">
                                    <div class="mr-4">
                                        <svg class="w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#triproute" />
                                        </svg>
                                    </div>

                                    <div>
                                        <div class="text-sm font-bold text-gray">
                                            Trip Route
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->trip_route ?? '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="px-3">

                                <h3 class="mb-2 text-2xl font-display text-primary">Highlights</h3>
                                <ul class="mb-4 highlights">
                                    {!! $trip->trip_info ? $trip->trip_info->highlights : '' !!}
                                </ul>

                                <div id="overview-text" style="margin-bottom: 15px;" class="prose">
                                    <?= $trip->trip_info ? $trip->trip_info->overview : '' ?>
                                </div>
                                {{-- <p class="text-center">
                                <button id="toggle-overview" class="btn btn-gray" data-bs-toggle="collapse" data-bs-target="#overview-text">Show
                                    More</button>
                            </p> --}}

                                <div class="p-4 mb-3 bg-light">
                                    <h3 class="mb-2 text-xl font-display text-primary"> Trek Profile</h3>
                                    <p class="mb-0 text-sm">
                                        {!! $trip->trip_info ? $trip->trip_info->important_note : '' !!}
                                    </p>
                                </div>
                            </div>
                        </div>


                    </div>

                    <!--<div class='mb-4 embed-container'><iframe src='https://www.youtube.com/embed//dFLxa0VwY-E' frameborder='0' allowfullscreen></iframe></div>-->

                    <div id="itinerary" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10" x-data="{
                        day1Open: true,
                        @for ($i = 1; $i < $trip->trip_itineraries->count() ; $i++)
                        day{{ $i + 1 }}Open:false, @endfor
                    }">
                        <div class="flex flex-wrap items-end justify-between mb-4">
                            <h2 class="text-4xl uppercase lg:text-5xl font-display text-primary">Trip Itinerary</h2>
                            <div>
                                <button class="mb-2 btn btn-sm btn-primary expand-all"
                                    @click="
                                @for ($i = 0; $i < $trip->trip_itineraries->count() ; $i++)
                                    day{{ $i + 1 }}Open = @endfor
                                true">Expand
                                    All</button>
                                <button class="mb-2 btn btn-sm btn-primary collapse-all"
                                    @click="
                                @for ($i = 0; $i < $trip->trip_itineraries->count() ; $i++)
                                    day{{ $i + 1 }}Open = @endfor
                                false">Collapse
                                    All</button>
                            </div>
                        </div>
                        <div class="mb-4 itinerary">
                            @foreach ($trip->trip_itineraries as $i => $itinerary)
                                <div class="mb-2 border-light">
                                    <button class="flex items-center w-full p-2 text-left text-primary" :aria-expanded="day{{ $i + 1 }}Open" aria-controls="day{{ $i + 1 }}"
                                        @click="day{{ $i + 1 }}Open=!day{{ $i + 1 }}Open">
                                        <div class="flex items-center mr-4">
                                            <div class="mr-2 text-sm">Day</div>
                                            <div class="text-2xl font-display text-primary">
                                                {{ $itinerary->day }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between flex-grow-1">
                                            <h3 class="text-xl font-display">{{ $itinerary->name }}</h3>
                                            <svg class="flex-shrink-0 w-6 h-6" x-show="!day{{ $i + 1 }}Open">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                            </svg>
                                            <svg class="flex-shrink-0 w-6 h-6" x-show="day{{ $i + 1 }}Open">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#minus" />
                                            </svg>
                                        </div>
                                    </button>
                                    <div id="day{{ $i + 1 }}" class="p-4 border-top-light" x-cloak x-show.transition="day{{ $i + 1 }}Open">
                                        <!--<div class="grid gap-4 xl:grid-cols-3">-->
                                        {{-- <img src="{{ asset('assets/front/img/hero.jpg') }}" alt=""> --}}
                                        <div class="xl:col-span-2">
                                            <p>
                                                {!! $itinerary->description !!}
                                            </p>
                                        </div>
                                        <!--</div>-->
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="items-center justify-between p-4 lg:flex bg-light">
                            <div>
                                Not satisfied with this itinerary? <b class="text-primary">Make your own</b>.
                            </div>
                            <a href="{{ route('front.trips.customize', $trip->slug) }}" class="btn btn-sm btn-primary">Customize</a>
                        </div>
                    </div>

                    <div id="inclusions" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                        <div class="p-3 bg-white">
                            @if ($trip->trip_include_exclude)
                                <div class="grid gap-1 lg:grid-cols-2">
                                    <div>
                                        <h2 class="text-3xl uppercase lg:text-4xl font-display text-primary">Includes</h2>
                                        <ul class="includes">
                                            <?= $trip->trip_include_exclude->include ?>
                                        </ul>
                                    </div>

                                    <div>
                                        <h2 class="text-3xl uppercase lg:text-4xl font-display text-primary">Doesn't Include</h2>
                                        <ul class="excludes">
                                            <?= $trip->trip_include_exclude->exclude ?>
                                        </ul>
                                    </div>
                                </div>
                            @endif

                           
                        </div>
                    </div>

                    @if (!$trip->trip_departures->isEmpty())
                        <div id="date-price" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                            <div class="p-3 bg-white">
                                <div class="items-center justify-between mb-4 lg:flex">
                                    <h2 class="text-4xl uppercase lg:text-5xl font-display text-primary">Upcoming Departures
                                    </h2>

                                    <form action="">
                                        <div class="form-group">
                                            <select name="" id="" class="bg-light">
                                                <option selected disabled>Choose Month & Year</option>
                                                <option value="">Jan 2021</option>
                                                <option value="">Feb 2021</option>
                                                <option value="">Mar 2021</option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                                <div class="grid grid-cols-2 gap-1 md:grid-cols-3 xl:grid-cols-4">
                                    @foreach ($trip->trip_departures as $departure)
                                        @include('front.elements.tour_departure_card', $departure)
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (iterator_count($trip->trip_reviews))
                        <div id="reviews" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                            <div class="items-center justify-between mb-4 lg:flex">
                                <h2 class="text-4xl uppercase lg:text-5xl font-display text-primary">Reviews
                                </h2>

                                <div>
                                    <a href="{{ route('front.reviews.create') }}" class="mr-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#review-modal">
                                        Write a review</a>
                                </div>
                            </div>
                            <div class="grid gap-2 lg:grid-cols-1 lg:gap-3">

                                @foreach ($trip->trip_reviews()->where('status', 1)->get() as $review)
                                    <div class="p-4 review">
                                        <div class="review__content">
                                            <h2 class="mb-2 text-2xl font-display text-primary">{{ $review->title }}</h2>
                                            <p>{{ $review->review }}</p>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="mr-2">
                                                <img src="{{ $review->thumbImageUrl }}" alt="">
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $review->review_name }}</div>
                                                <div class="text-sm text-gray">{{ $review->review_country }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="{{ route('front.reviews.index') }}" class="theme">See more reviews
                                <svg>
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                                </svg>
                            </a>
                        </div>
                    @endif


                    {{-- <div class="mb-4">
                    <iframe src="https://www.google.com/maps/d/embed?mid=1o2LaX1o68hVBiycWHWDrK_F18H1epiGB" width="100%" height="480" class="border-none"></iframe>
                </div> --}}

                    @if (!$trip->trip_faqs->isEmpty())
                        <div id="faqs" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                            <h2 class="mb-4 text-4xl uppercase lg:text-5xl font-display text-primary">Frequently Asked Questions</h2>

                            <div class="mb-4" x-data="{ active: 'none' }">
                                @foreach ($trip->trip_faqs as $i => $faq)
                                    <div class="mb-1 border-light">
                                        <button class="flex items-center justify-between w-full p-2 text-left" @click="active = (active === {{ $i }} ? 'none' : {{ $i }})">
                                            <h3 class="text-xl font-display text-primary">{{ $faq->title }}</h3>

                                            <svg class="flex-shrink-0 w-6 h-6 text-primary" x-show="active!=={{ $i }}">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                            </svg>
                                            <svg class="flex-shrink-0 w-6 h-6 text-primary" x-show="active==={{ $i }}">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#minus" />
                                            </svg>
                                        </button>
                                        <div class="p-4" x-cloak x-show.transition="active==={{ $i }}">
                                            <p class="mb-0">
                                                {!! $faq->description !!}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="#" class="mb-2 theme">Read more FAQs
                                <svg>
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                                </svg>
                            </a>
                        </div>
                    @endif


                    <div class="flex flex-wrap justify-between mb-4">
                        <div class="flex mb-2">
                            <a href="{{ route('front.trips.booking', $trip->slug) }}" class="mr-2 btn btn-accent">Book Now</a>
                            <a href="{{ route('front.trips.customize', $trip->slug) }}" class="btn btn-primary">
                                <svg class="w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#adjustments" />
                                </svg>
                                Customize
                            </a>
                        </div>
                        <div class="flex">
                            <a href="#" class="flex items-center p-1 mr-2 text-accent" title="Print tour details">
                                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#printer" />
                                </svg>
                                <span>Print Tour Details</span>
                            </a>
                            <a href="#" class="flex items-center p-1 text-accent" title="">
                                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#download" />
                                </svg>
                                <span>Download Tour Brochure</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h2 class="mb-2 uppercase lg:text-xl font-display text-primary">Share this tour</h2>
                        <a href="#" class="mr-2 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#facebook" />
                            </svg>
                        </a>
                        <a href="#" class="mr-2 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#twitter" />
                            </svg>
                        </a>
                        <a href="#" class="text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#instagram" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- aside --}}
                <aside class="pt-10">

                    @include('front.elements.price_card')
                    <a href="{{ route('front.trips.booking', $trip->slug) }}" class="w-full mb-8 btn btn-accent">Ask for agency price</a>

                    @include('front.elements.enquiry')

                    <!-- Route Map -->
                 @if($trip->map_file_name)
                <div class="mb-8">
                    <div class="card-header">
                        <h2 class="mb-2 text-2xl uppercase font-display text-primary">Map & Route</h2>
                    </div>
                    <div class="card-body p-0">
                        <a data-fancybox data-caption="Route Map" href="{{ $trip->mapImageUrl }}">
                            <img class="img-fluid" src="{{ $trip->mapImageUrl }}" alt="{{ $trip->name }}">
                        </a>
                    </div>
                </div>
                 @endif

                    <div class="px-2 py-10 text-white experts-card bg-primary">
                        <div class="grid grid-cols-3">
                            <div class="col-span-2">
                                <p class="mb-0">Still confused?</p>
                                <h3 class="mb-2">Talk to our experts</h3>
                            </div>
                            <div>
                                <svg class="w-20 h-20">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#customersupport" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex mb-1 experts-phone">
                            <a href="{{ Setting::get('mobile1') }}" class="flex aic">
                                <svg class="w-6 h-6 mr-1">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#phone" />
                                </svg>
                                {{ Setting::get('mobile1') }}
                            </a>
                        </div>
                        <div class="flex mb-3 experts-phone">
                            <a href="mailto:{{ Setting::get('email') }}" class="flex aic">
                                <svg class="w-6 h-6 mr-1">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#mail" />
                                </svg>
                                {{ Setting::get('email') }}
                            </a>
                        </div>
                    </div>

                    <div class="p-2 mb-8 bg-light">
                        <a href="{{ Setting::get('facebook') }}" class="mr-1 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#facebookmessenger" />
                            </svg>
                        </a>
                        <a href="{{ Setting::get('viber') }}" class="mr-1 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viber" />
                            </svg>
                        </a>
                        <a href="{{ Setting::get('whatsapp') }}" class="mr-1 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#whatsapp" />
                            </svg>
                        </a>
                        <a href="{{ Setting::get('skype') }}" class="mr-1 text-primary hover:text-accent">
                            <svg class="w-6 h-6">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#skype" />
                            </svg>
                        </a>
                        {{-- <a href="{{ Setting::get('weixin') }}" class="mr-1 text-primary hover:text-accent">
                        <svg class="w-6 h-6">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#weixin" />
                        </svg>
                    </a> --}}
                    </div>



                    @if (iterator_count($trip->addon_trips))
                        <div class="mb-8">
                            <h2 class="mb-2 text-2xl uppercase font-display text-primary">Additional Tours</h2>
                            @forelse ($trip->addon_trips as $addon_trip)
                                @include('front.elements.addon_trip', ['trip' => $addon_trip])
                            @empty
                            @endforelse
                        </div>
                    @endif

                    <div class="sticky-top sticky-price none lg:block" style="top: 9rem;">
                        @include('front.elements.price_card')
                    </div>

                </aside>
            </div>
        </div>

        <!-- Similar -->
        @if (!$trip->similar_trips->isEmpty())
            <div class="py-10 bg-light ">
                <div class="container">
                    <h2 class="text-4xl uppercase lg:text-5xl font-display text-primary">Similar Tours</h2>
                    <div class="grid gap-2 md:grid-cols-2 lg:grid-cols-3 md:gap-4">
                        @forelse ($trip->similar_trips as $trip)
                            @include('front.elements.tour-card', ['tour' => $trip])
                        @empty
                        @endforelse
                    </div>
                </div>
            </div> <!-- Similar -->
        @endif
    </section>

    {{-- trips of the month 
    <div class="py-10 text-white bg-primary">
        <div class="container">

            <div class="items-center justify-between mb-4 lg:flex">
                <h1 class="text-4xl uppercase lg:text-5xl font-display">Trips of the month
                </h1>

                <div class="trips-month-slider-controls">
                    <button>
                        <svg class="w-6 h-6 text-accent">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowleft" />
                        </svg>
                    </button>
                    <button>
                        <svg class="w-6 h-6 text-accent">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
    --}}
    <div class="fixed bottom-0 left-0 right-0 px-4 py-2 bg-white shadow-sm lg:none">
        <a href="" class="w-full mb-2 btn btn-primary">Book Now</a>
    </div>
@endsection
@push('scripts')
    <!--<script src="{{ asset('assets/front/js/tour-details.js') }}"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/wheelzoom@4.0.1/wheelzoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>
    <script>
        // jQuery.noConflict(true);
    </script>
    <script>
        wheelzoom(document.querySelector('.wheelzoom'))
    </script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const heroSlider = tns({
                container: '.hero-slider',
                nav: false,
                controlsContainer: '.hero-slider-controls > div',
                autoplay: true,
                autoplayButtonOutput: false
            })
            const monthSlider = tns({
                container: '.trips-month-slider',
                nav: false,
                controlsContainer: '.trips-month-slider-controls',
                autoplay: true,
                autoplayButtonOutput: false
            });


            // For scrollspy functionality
            const tdb = document.querySelector('.tdb')
            const sections = document.querySelectorAll('.tds')
            const sectionScrollObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    const link = tdb.querySelector(`[href="#${entry.target.id}"]`)
                    if (entry.isIntersecting) {
                        link.classList.add('bg-accent')
                    } else {
                        link.classList.remove('bg-accent')
                    }
                })
            }, {
                rootMargin: "-19% 0px -80% 0px"
            })
            sections.forEach(section => {
                sectionScrollObserver.observe(section)
            })

        })
        window.onload = function() {

            var session_success_message = '{{ $session_success_message ?? '' }}';
            var session_error_message = '{{ $session_error_message ?? '' }}';
            if (session_success_message) {
                toastr.success(session_success_message);
            }

            if (session_error_message) {
                toastr.danger(session_error_message);
            }

            // Hero Slider
            //   $(".tour-details-hero .owl-carousel").owlCarousel({
            //     items: 1,
            //     dots: false,
            //     // autoplay: true,
            //     // autoplayTimeout: 8000,
            //     loop: true,
            //     animateOut: 'fadeOut'
            //   });

            // $("#review-modal").modal('show');

            //Display user image upon select
            const showImage = (src, target) => {
                var fr = new FileReader();
                // when image is loaded, set the src of the image where you want to display it
                fr.onload = function(e) {
                    target.src = this.result;
                };
                src.addEventListener("change", function() {
                    // fill fr with image data
                    fr.readAsDataURL(src.files[0]);
                });
            }
            const src = document.getElementById("photo-input");
            const target = document.getElementById("write-review-photo");
            //   showImage(src, target);

            //Control ratings
            //   const stars = document.querySelectorAll('.select-ratings i')
            //   const ratingsInput = document.querySelector('#ratings-input')
            //   stars.forEach((star, index) => {
            //     star.addEventListener('click', () => {
            //       ratingsInput.value = index + 1
            //       console.log(ratingsInput.value)
            //       stars.forEach((star, indexx) => {
            //         star.classList.remove('active')
            //         if (indexx <= index) star.classList.add('active')
            //       })
            //     })
            //   })
        }
        $(function() {
            function expandAll() {}

            $('#map-modal').on('show.bs.modal', function(e) {
                setTimeout(function() {
                    let img = '<img class="img-fluid map-image-modal" src="{{ $mapImageUrl }}" alt="">';
                    $("#map-modal").find(".modal-body").html(img);
                    wheelzoom($('.map-image-modal'));
                }, 500);
            });
            // $(".similar-trip-rating").rating();
            // $("#review-rating").rating();
        });
    </script>
    <script>
        $(function() {
            // var validator = $("#review-form").validate({
            //     ignore: "",
            //     rules: {
            //         'name': 'required',
            //         'country': 'required',
            //         'title': 'required',
            //         'review': 'required',
            //     },
            //     submitHandler: function(form, event) {
            //         event.preventDefault();
            //         if (grecaptcha.getResponse(1)) {
            //             var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Submitting...');
            //             setTimeout(() => {
            //                 form.submit();
            //             }, 500);
            //         }else{
            //             grecaptcha.reset(review_captcha);
            //             grecaptcha.execute(review_captcha);
            //         }
            //     },
            // });

            var enquiry_validator = $("#enquiry-form").validate({
                ignore: "",
                rules: {
                    'name': 'required',
                    'email': 'required',
                    'country': 'required',
                    'phone': 'required',
                    'message': 'required',
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element.closest('.flex'));
                    // error.append(element.closest('.form-group'));
                },
                submitHandler: function(form, event) {
                    event.preventDefault();
                    $(form).find('#redirect-url').val('{!! route('front.trips.show', $trip->slug) !!}');
                    if (grecaptcha.getResponse(0)) {
                        var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Sending...');
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    } else {
                        grecaptcha.reset(enquiry_captcha);
                        grecaptcha.execute(enquiry_captcha);
                    }
                },
            });
        });

        function onSubmitReview(token) {
            $("#review-form").submit();
            return true;
        }

        function onSubmitEnquiry(token) {
            $("#enquiry-form").submit();
            return true;
        }

        let enquiry_captcha;
        let review_captcha;
        var CaptchaCallback = function() {
            enquiry_captcha = grecaptcha.render('inquiry-g-recaptcha', {
                'sitekey': '{!! config('constants.recaptcha.sitekey') !!}'
            });
            // review_captcha = grecaptcha.render('review-g-recaptcha', {'sitekey' : '{!! config('constants.recaptcha.sitekey') !!}'});
        };
    </script>
@endpush
