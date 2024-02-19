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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0.23/dist/fancybox/fancybox.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css">
        <style>
            .blocker {
                z-index: 10000 !important;
            }

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

            .modal {
                z-index: 99999 !important;
            }

            canvas#ctx {
                background: center / cover url({{ asset('assets/front/img/mountains.jpg') }});
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

            .trip-map-iframe {
                display: flex;
            }

            .prose-lim p {
                margin-top: 1.25em;
                margin-bottom: 1.25em;
            }

            .prose-lim a {
                text-decoration: underline;
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
                        <img src="{{ $gallery->imageUrl }}" class="object-cover w-full h-96 md:h-[36rem] lg:h-[48rem]" alt="">
                    </div>
                @endforeach
            @endif
        </div>

        <div class="absolute bottom-0 w-full py-10 bg-gradient-to-b from-black/0 to-black/30">
            <div class="container flex flex-wrap items-center gap-10">
                <div>
                    <h1 class="mb-2 text-3xl font-semibold text-white lg:text-4xl font-display">
                        <span>{{ $trip->name }}</span>
                    </h1>

                    <div class="hidden text-white breadcrumb-wrapper md:block">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb fs-sm wrap">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ route('front.trips.listing') }}">Trips</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $trip->name }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>

                <div class="hidden ratings-wrapper lg:block" style="background: #00900cf2; border-radius: 6px;">
                    <div class="px-6 py-4 text-white rounded ratings d-flex align-items-center ">
                        <div class="flex justify-center mb-2">
                            @for ($i = 0; $i < $trip->rating; $i++)
                                <svg class="w-6 h-6 text-accent">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#star" />
                                </svg>
                            @endfor

                            @for ($i = 0; $i < 5 - $trip->rating; $i++)
                                <svg class="w-6 h-6 text-accent" viewbox="0 0 20 20" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-width="1.5"
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                    </path>
                                </svg>
                            @endfor
                        </div>
                        <div class="text-sm">from {{ $trip->reviews_count }} reviews</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="absolute hidden hero-slider-controls md:block">
            <div class="container flex gap-4">
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

    <section class="bg-gray-100">
        <!-- Sticky Nav -->
        <div class="sticky text-white bg-primary tdb" style="top:5rem;z-index:98">
            <div class="container flex items-center justify-center">
                <nav class="flex items-center justify-center tour-details-tabs" id="secondnav">
                    <ul class="flex flex-wrap gap-1 py-1 nav">
                        <li>
                            <a href="#overview" class="flex items-center p-3 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viewgrid" />
                                </svg>
                                <span class="none md:block">Overview</span>
                            </a>
                        </li>

                        @if (!$trip->trip_itineraries->isEmpty())
                            <li>
                                <a href="#itinerary" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#clock" />
                                    </svg>
                                    <span class="none md:block">Itinerary</span></a>
                            </li>
                        @endif

                        <li>
                            <a href="#gallery" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="w-6 h-6 md:mr-2" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                                    </path>
                                </svg>
                                <span class="hidden text-sm md:block">Gallery</span>
                            </a>
                        </li>

                        @if ($trip->trip_include_exclude)
                            <li>
                                <a href="#inclusions" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#archive" />
                                    </svg>
                                    <span class="none md:block">Inclusions</span>
                                </a>
                            </li>
                        @endif

                        @if (!$trip->trip_departures->isEmpty())
                            <li>
                                <a href="#date-price" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendar" />
                                    </svg>
                                    <span class="none md:block">Date & Price</span>
                                </a>
                            </li>
                        @endif

                        <li>
                            <a href="#reviews" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#chat" />
                                </svg>
                                <span class="none md:block">Review</span>
                            </a>
                        </li>

                        @if ($trip->trip_seo->about_leader)
                            <li>
                                <a href="#equipment-list" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z">
                                        </path>
                                    </svg>
                                    <span class="none md:block">Equipment List</span>
                                </a>
                            </li>
                        @endif

                        @if (!$trip->trip_faqs->isEmpty())
                            <li>
                                <a href="#faqs" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#questionmarkcircle" />
                                    </svg>
                                    <span class="none md:block">FAQs</span>
                                </a>
                            </li>
                        @endif

                    </ul>
                </nav>
            </div>
            <div id="tourDetailsBarIO"></div>
        </div><!-- Sticky Nav -->

        <div class="container pb-20 mt-10">

            <div class="grid gap-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-10">

                <div class="relative tour-details lg:col-span-2 xl:col-span-3">

                    <div class="lg:hidden">
                        @include('front.elements.price_card')
                    </div>

                    <div id="overview" class="px-4 pt-10 pb-4 mb-4 bg-white rounded-lg tds lg:px-10">
                        <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                            <div class="flex aic">
                                <div class="mr-4">
                                    <svg class="w-10 h-10 text-primary">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendarduration" />
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
                                        Accommodation
                                    </div>
                                    <div>
                                        {!! $trip->trip_info->accomodation ?? '' !!}
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
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
                                    <div class="text-sm font-semibold text-gray">
                                        Trip Route
                                    </div>
                                    <div>
                                        {{ $trip->trip_info->trip_route ?? '' }}
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="px-3">

                            <h3 class="mb-2 text-xl text-gray-600">Highlights</h3>
                            <ul class="mb-4 highlights">
                                {!! $trip->trip_info ? $trip->trip_info->highlights : '' !!}
                            </ul>

                            <div id="overview-text" x-data="{ expanded: false }" class="relative mb-4 prose" style="max-width: 90ch;">
                                <div x-show="expanded" class="pb-20" x-collapse.min.200px><?= $trip->trip_info ? $trip->trip_info->overview : '' ?></div>
                                <div class="absolute bottom-0 flex justify-center w-full py-4" style="background: linear-gradient(to top, rgba(255,255,255,1), rgba(255,255,255,0));"><button
                                        class="px-4 py-2 text-xs rounded-full bg-light" x-on:click="expanded=!expanded" x-text="expanded?'Show less':'Show more'">Show more</button></div>
                            </div>

                            <div class="p-4 mb-3 bg-light">
                                <h3 class="mb-2 text-xl font-display text-primary"> Important Note</h3>
                                <p class="mb-0 text-sm">
                                    {!! $trip->trip_info ? $trip->trip_info->important_note : '' !!}
                                </p>

                            </div>
                        </div>
                    </div>

                    <!--<div class='mb-4 embed-container'><iframe src='https://www.youtube.com/embed//dFLxa0VwY-E' frameborder='0' allowfullscreen></iframe></div>-->

                    <div id="itinerary" class="px-4 pt-10 pb-4 mb-4 bg-white rounded-lg tds lg:px-10" x-data="{
                        day1Open: true,
                        @for ($i = 1; $i < $trip->trip_itineraries->count() ; $i++)
                        day{{ $i + 1 }}Open:false, @endfor
                    }">
                        <div class="flex flex-wrap items-end justify-between mb-4">
                            <h2 class="text-xl text-gray-600 lg:text-2xl">Trip Itinerary</h2>
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
                                <div class="mb-2 border border-gray-200 rounded">
                                    <button class="flex items-center w-full px-4 py-3 text-left text-gray-600" :aria-expanded="day{{ $i + 1 }}Open" aria-controls="day{{ $i + 1 }}"
                                        @click="day{{ $i + 1 }}Open=!day{{ $i + 1 }}Open">
                                        <div class="flex items-center mr-4">
                                            <div class="mr-2 text-sm">Day</div>
                                            <div class="text-2xl font-display text-primary">
                                                {{ $itinerary->day }}
                                            </div>
                                        </div>
                                        <div class="flex justify-between flex-grow">
                                            <h3 class="text-lg">{{ $itinerary->name }}</h3>
                                            <svg class="flex-shrink-0 w-6 h-6" x-show="!day{{ $i + 1 }}Open">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                            </svg>
                                            <svg class="flex-shrink-0 w-6 h-6" x-show="day{{ $i + 1 }}Open">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#minus" />
                                            </svg>
                                        </div>
                                    </button>
                                    <div id="day{{ $i + 1 }}" x-cloak x-show.transition="day{{ $i + 1 }}Open">
                                        <div class="prose-lim">
                                            @if (isset($itinerary->image_name) && !empty($itinerary->image_name))
                                                <a href="{{ $itinerary->imageUrl }}" data-fancybox="day{{ $i }}"
                                                    class="mt-2 mb-2 xl:w-1/2 {{ $i % 2 == 0 ? 'xl:float-left mr-4' : 'xl:float-right ml-4' }}">
                                                    <img src="{{ $itinerary->imageUrl }}" alt="" loading="lazy">
                                                </a>
                                            @endif
                                            <div class="p-4">
                                                <p>
                                                    {!! $itinerary->description !!}
                                                </p>

                                            </div>
                                        </div>
                                        {{-- icons --}}
                                        @if ($itinerary->max_altitude || $itinerary->accomodation || $itinerary->meals)
                                            <div class="flex flex-col justify-between gap-4 bg-gray md:flex-row">
                                                @if ($itinerary->max_altitude)
                                                    <div class="flex gap-2 p-4">
                                                        <img src="{{ asset('assets/front/img/elevation.png') }}" alt="" class="w-10 h-10">
                                                        <div>
                                                            <h4 class="text-sm font-semibold">Max. altitude</h4>
                                                            <div>{{ $itinerary->max_altitude }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($itinerary->accomodation)
                                                    <div class="flex gap-2 p-4">
                                                        <img src="{{ asset('assets/front/img/accomodation.png') }}" alt="" class="w-10 h-10">
                                                        <div>
                                                            <h4 class="text-sm font-semibold">Accomodation</h4>
                                                            <div>{{ $itinerary->accomodation }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if ($itinerary->meals)
                                                    <div class="flex gap-2 p-4">
                                                        <img src="{{ asset('assets/front/img/meal.png') }}" alt="" class="w-10 h-10">
                                                        <div>
                                                            <h4 class="text-sm font-semibold">Meals</h4>
                                                            <div>{{ $itinerary->meals }}</div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                        <!--</div>-->
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="items-center justify-between p-4 lg:flex bg-light">
                            <div>
                                Not satisfied with this itinerary? <b class="text-primary">Make your own</b>.
                            </div>
                            <a href="{{ route('front.plantrip.createfortrip', $trip->slug) }}" class="btn btn-sm btn-primary">Plan My Trip</a>
                        </div>
                    </div>

                    @if ($canMakeChart)
                        <div class="px-4 pt-10 pb-4 mb-4 bg-white rounded-lg lg:px-10">
                            <figure class="border border-gray-100">
                                <figcaption class="mt-6 text-center">Elevation Chart</figcaption>
                                <div style="overflow-x: scroll;">
                                    <div id="chart-wrapper">
                                        <canvas id="ctx"></canvas>
                                    </div>
                                </div>
                            </figure>
                        </div>
                        @push('scripts')
                            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
                            <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
                            <script>
                                const ctx = document.getElementById('ctx');

                                Chart.register(ChartDataLabels);

                                // const labels = ['Kathmandu', 'Kathmandu', 'Phakding', 'Namche Bazar', 'Namche Bazar', 'Tyangboche', 'Dingboche', 'Chukung', 'Lobuche', 'Gorakshep', 'Pheriche', 'Kyangjuma', 'Monjo', 'Lukla', 'Kathmandu'];
                                const labels = {!! json_encode(array_column($elevations, 'place_name')) !!};

                                const chartWrapper = document.getElementById('chart-wrapper');

                                chartWrapper.style.height = '400px';
                                if (labels.length > 10) {
                                    chartWrapper.style.width = labels.length * 70 + 'px';
                                    chartWrapper.style.maxWidth = labels.length * 70 + 'px';
                                }

                                new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: labels,
                                        datasets: [{
                                            label: 'Max. elevation (metres)',
                                            data: {!! json_encode(array_column($elevations, 'max_altitude')) !!},
                                            fill: true,
                                            backgroundColor: '#93cd0620',
                                            borderWidth: 1,
                                            borderColor: '#3eb368',
                                            pointBackgroundColor: '#3eb368',
                                        }]
                                    },
                                    options: {
                                        animation: false,
                                        maintainAspectRatio: false,
                                        layout: {
                                            padding: {
                                                left: 40,
                                                right: 40,
                                                bottom: 0
                                            }
                                        },
                                        plugins: {
                                            tooltip: {
                                                enabled: true,
                                                usePointStyle: true,
                                                callbacks: {
                                                    labelPointStyle: function(context) {
                                                        return {
                                                            pointStyle: 'triangle',
                                                            rotation: 0
                                                        };
                                                    },
                                                    label: function(context) {
                                                        let label = context.dataset.label || '';

                                                        if (label) {
                                                            label += ': ';
                                                        }
                                                        if (context.parsed.y !== null) {
                                                            label += context.parsed.y + 'm'
                                                        }
                                                        return label;
                                                    }
                                                }
                                            },
                                            datalabels: {
                                                color: '#3eb368',
                                                align: 'top',
                                                offset: 10,
                                                formatter: function(value, ctx) {
                                                    return ctx.chart.data.labels[ctx.dataIndex] + '\n' + value + ' m';
                                                    //   return `${value} m`;
                                                },
                                            },
                                            legend: {
                                                display: false
                                            },
                                        },
                                        scales: {
                                            x: {
                                                display: false
                                            },
                                            y: {
                                                display: false,
                                                max: 6500
                                            }
                                        }
                                    }
                                });
                            </script>
                        @endpush
                    @endif

                    {{-- Gallery --}}
                    @if ($trip->trip_galleries)
                        <div id="gallery" class="px-4 py-10 mb-4 bg-white rounded-lg tds lg:px-10">
                            <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl ">Gallery</h2>
                            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                                @foreach ($trip->trip_sliders as $gallery)
                                    <a href="{{ $gallery->imageUrl }}" data-fancybox="tripGallery">
                                        <img src="{{ $gallery->imageUrl }}" loading="lazy" class="object-cover aspect-square">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    {{-- Gallery --}}

                    @push('scripts')
                        <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0.23/dist/fancybox/fancybox.umd.min.js"></script>
                        <script>
                            Fancybox.bind("[data-fancybox]", {});
                        </script>
                    @endpush

                    {{-- Inclusions --}}
                    <div id="inclusions" class="px-4 py-10 mb-4 bg-white rounded-lg tds lg:px-10">
                        <div class="p-3 bg-white">
                            @if ($trip->trip_include_exclude)
                                <div class="grid gap-10 lg:grid-cols-2">
                                    <div>
                                        <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl ">Includes</h2>
                                        <ul class="includes">
                                            <?= $trip->trip_include_exclude->include ?>
                                        </ul>
                                    </div>

                                    <div>
                                        <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl ">Doesn't Include</h2>
                                        <ul class="excludes">
                                            <?= $trip->trip_include_exclude->exclude ?>
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- Inclusions --}}

                    {{-- departure dates --}}
                    @if (!$trip->trip_departures->isEmpty())
                        <div id="date-price" class="px-4 py-10 mb-4 bg-white rounded-lg lg:px-10 tds">
                            <div class="flex flex-wrap items-center justify-between gap-10 mb-4">
                                <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl ">Upcoming Departure Dates
                                </h2>
                                <div class="flex gap-2">
                                    <button id="group-departure" class="flex items-center gap-2 p-2 text-sm border border-gray-200 rounded hover:text-primary hover:border-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4" viewBox="0 0 16 16">
                                            <path
                                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4" />
                                        </svg>
                                        Group departures
                                    </button>
                                    <button id="private-departure" class="flex items-center gap-2 p-2 text-sm border rounded hover:text-primary hover:border-primary border-primary text-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-4 h-4" viewBox="0 0 16 16">
                                            <path
                                                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664z" />
                                        </svg>
                                        Private departures
                                    </button>
                                </div>
                            </div>
                            <?php
                            $currentYear = date('Y');
                            $currentMonth = date('n');
                            $monthsArray = [];
                            for ($i = 0; $i < 26; $i++) {
                                $year = $currentYear;
                                $month = $currentMonth + $i;
                                if ($month > 24) {
                                    break;
                                } elseif ($month > 12) {
                                    $month -= 12;
                                    $year++;
                                }
                                $monthsArray[] = strtotime("$year-$month-01");
                            }
                            ?>
                            <div id="all-dates-block" class="grid grid-cols-4 gap-2 mb-4 md:grid-cols-6 lg:grid-cols-9">
                                <button id="all-departure-filter" class="p-2 px-4 py-2 font-semibold text-center text-white border rounded departure-date-active border-primary bg-primary"> All <br>
                                    Dep</button>
                                @foreach ($monthsArray as $month)
                                    <button data-date="{{ $month }}"
                                        class="p-2 px-4 py-2 font-semibold text-center border border-gray-200 rounded select-date-departure hover:border-primary hover:text-primary">{{ Str::replaceFirst('-', '<br>', date('M Y', $month)) }}</button>
                                @endforeach
                            </div>

                            <div class="grid gap-4 mb-6">
                                <?php $trip_departures = $trip->trip_departures; ?>
                                <div id="departure-filter-block" class="grid gap-4">
                                    @foreach ($trip_departures as $departure)
                                        <div class="relative grid grid-cols-2 gap-4 p-4 border border-gray-200 rounded lg:grid-cols-5 lg:place-items-center hover:border-primary">
                                            <div class="absolute top-0 px-1 text-xs text-gray-400 bg-white border border-gray-100 rounded-full left-4" style="translate: 0 -50%;">Group</div>
                                            <div class="absolute top-0 right-0 w-10 h-10 overflow-hidden rounded">
                                                <div class="w-16 px-1 pt-4 text-xs text-center text-white bg-red-600" style="rotate: 45deg; margin-top: -8px">-10%</div>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ formatDate($departure->from_date) }}</div>
                                                <div class="text-sm text-gray-400">From {{ $trip->starting_point }}</div>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ formatDate($departure->to_date) }}</div>
                                                <div class="text-sm text-gray-400">To {{ $trip->starting_point }}</div>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ $departure->seats }}</div>
                                                <div class="text-sm text-gray-400">people booked</div>
                                            </div>
                                            <div>
                                                <div class="font-semibold">From <span class="text-red"><s>US $ {{ number_format($trip->cost) }}</s></span></div>
                                                <div class="text-lg font-semibold">US$ {{ number_format($departure->price) }}</div>
                                                <div class="text-sm"><span class="text-gray-400">Saving </span>US$ {{ number_format($trip->cost - $departure->price) }}</div>
                                            </div>
                                            <div class="flex items-center">
                                                <a href="{{ route('front.trips.departure-booking', ['slug' => $trip->slug, 'id' => $departure->id]) }}"
                                                    class="px-3 py-2 text-sm border rounded border-primary text-primary hover:bg-primary hover:text-white">Book Now</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- bicky --}}
                                <div style=" display: flex; justify-content: center;">
                                    <button id="show-more-departure-button" style="display: none;" class="px-4 py-2 text-xs rounded-full bg-light">Show more</button>
                                </div>
                            </div>

                        </div>
                    @endif
                    {{-- departure dates --}}

                    {{-- reviews --}}
                    @if (iterator_count($trip->trip_reviews))
                        <div id="reviews" class="px-4 py-10 mb-4 bg-white rounded-lg tds lg:px-10">
                            <div class="items-center justify-between mb-4 lg:flex">

                                <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl">Reviews</h2>

                                <div>
                                    <a href="{{ route('front.reviews.create') }}" class="mr-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#review-modal">
                                        Write a review</a>
                                </div>
                            </div>
                            <div class="grid gap-4 mb-4 lg:grid-cols-1 lg:gap-3">
                                @foreach ($trip->trip_reviews()->where('status', 1)->limit(3)->get() as $review)
                                    <div class="p-4 mr-auto border border-gray-200 lg:p-10 rounded-xl review">
                                        <div class="prose">
                                            <h2 class="mb-2 text-2xl font-display text-primary">{{ $review->title }}</h2>
                                            <p>{{ $review->review }}</p>
                                        </div>
                                        <div class="flex items-center gap-4 mt-4">
                                            <div class="mr-2">
                                                <img src="{{ $review->thumbImageUrl }}" alt="">
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ $review->review_name }}</div>
                                                <div class="text-sm text-gray">{{ $review->review_country }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <a href="{{ route('front.reviews.index') }}" class="underline text-primary">See more reviews
                            </a>
                        </div>
                    @endif

                    {{-- equipment List --}}
                    @if ($trip->trip_seo->about_leader)
                        <div id="equipment-list" class="px-4 pt-10 pb-4 mb-4 bg-white rounded-lg tds lg:px-10">
                            <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl">Equipment List</h2>
                            <div class="prose">
                                {!! $trip->trip_seo->about_leader !!}
                            </div>
                        </div>
                    @endif
                    {{-- equipment List --}}

                    {{-- faqs --}}
                    @if (!$trip->trip_faqs->isEmpty())
                        <div id="faqs" class="px-4 py-10 mb-4 bg-white rounded-lg tds lg:px-10">
                            <h2 class="mb-4 text-xl text-gray-600 lg:text-2xl">Frequently Asked Questions</h2>

                            <div class="mb-4" x-data="{ active: 'none' }">
                                @foreach ($trip->trip_faqs as $i => $faq)
                                    <div class="mb-2 border border-gray-200 rounded">
                                        <button class="flex items-center justify-between w-full px-4 py-3 text-left" @click="active = (active === {{ $i }} ? 'none' : {{ $i }})">
                                            <h3 class="text-lg text-gray-600">{{ $faq->title }}</h3>

                                            <svg class="flex-shrink-0 w-6 h-6 text-gray-600" x-show="active!=={{ $i }}">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                            </svg>
                                            <svg class="flex-shrink-0 w-6 h-6 text-gray-600" x-show="active==={{ $i }}">
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

                            {{-- <a href="#" class="mb-2 underline text-primary">Read more FAQs
                            </a> --}}
                        </div>
                    @endif
                    {{-- faqs --}}

                    <div class="flex flex-wrap justify-between mb-4">
                        <div class="flex mb-2">
                            <a href="{{ route('front.trips.booking', $trip->slug) }}" class="mr-2 btn btn-accent">Book Now</a>
                            <a href="{{ route('front.plantrip.createfortrip', $trip->slug) }}" class="btn btn-primary">
                                <svg class="w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#adjustments" />
                                </svg>
                                Plan My Trip
                            </a>
                        </div>

                        <div class="flex">
                            <a href="{{ route('front.trips.print', ['slug' => $trip->slug]) }}" class="flex items-center p-1 mr-2 text-primary" title="Print tour details">
                                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#printer" />
                                </svg>
                                <span>Print Tour Details</span>
                            </a>
                            <a href="#" class="flex items-center p-1 text-primary" title="">
                                <svg class="flex-shrink-0 w-6 h-6 mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#download" />
                                </svg>
                                <span>Download Tour Brochure</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h2 class="mb-2 uppercase lg:text-xl font-display text-primary">Share this tour</h2>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.trips.show', ['slug' => $trip->slug]) }}" class="mr-2 text-primary hover:text-accent">
                                <svg class="w-6 h-6">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#facebook" />
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ route('front.trips.show', ['slug' => $trip->slug]) }}&text=" class="mr-2 text-primary hover:text-accent">
                                <svg class="w-6 h-6">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#twitter" />
                                </svg>
                            </a>
                            <a href="{{ Setting::get('instagram') }}" class="text-primary hover:text-accent">
                                <svg class="w-6 h-6">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#instagram" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- aside --}}
                <aside>

                    @include('front.elements.price_card')
                    <a href="{{ route('front.trips.booking', $trip->slug) }}" class="w-full mb-8 btn btn-accent">Ask for agency price</a>

                    @include('front.elements.enquiry')

                    <!-- Route Map -->
                    @if ($trip->map_file_name)
                        <div class="mb-8">
                            <div class="card-header">
                                <h2 class="mb-2 text-2xl uppercase font-display text-primary">Map & Route</h2>
                            </div>
                            <div class="p-0 card-body">
                                <!-- Link to open the modal -->
                                <a href="#ex1" rel="modal:open">
                                    <img class="img-fluid" src="{{ $trip->mapImageUrl }}" alt="{{ $trip->name }}">
                                </a>
                            </div>
                        </div>
                    @endif

                    @if (!empty($trip->iframe))
                        <div class="mb-8">
                            <div class="card-header">
                                <h2 class="mb-2 text-2xl uppercase font-display text-primary">Map</h2>
                            </div>
                            <div class="p-0 card-body">
                                <!-- Link to open the modal -->
                                <div class="trip-map-iframe">
                                    {!! $trip->iframe !!}
                                </div>
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

                    <div class="flex justify-around p-2 mb-8 bg-light">
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

                    @include('front.elements.essential_trip_information')

                    @if (iterator_count($trip->addon_trips))
                        <div class="mb-8">
                            <h2 class="mb-2 text-2xl text-primary font-display">Additional Tours</h2>
                            @forelse ($trip->addon_trips as $addon_trip)
                                @include('front.elements.addon_trip', ['trip' => $addon_trip])
                            @empty
                            @endforelse
                        </div>
                    @endif

                    <div class="sticky hidden lg:block" style="top: 9rem;">
                        @include('front.elements.price_card')
                    </div>

                </aside>
            </div>
        </div>

        <!-- Similar -->
        @if (!$trip->similar_trips->isEmpty())
            <div class="py-10 bg-light ">
                <div class="container">
                    <h2 class="mb-4 text-2xl uppercase lg:text-3xl font-display text-primary">Similar Tours</h2>
                    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                        @forelse ($trip->similar_trips as $s_trip)
                            @include('front.elements.tour-card', ['tour' => $s_trip])
                        @empty
                        @endforelse
                    </div>
                </div>
            </div> <!-- Similar -->
        @endif
    </section>
    
    <div id="ex1" class="modal" style="max-width: 70%;">
        <p>
            <img class="map-image-modal" src="{{ $trip->mapImageUrl }}" alt="map">
        </p>

    </div>
@endsection
@push('scripts')
    <!--<script src="{{ asset('assets/front/js/tour-details.js') }}"></script>-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/wheelzoom@4.0.1/wheelzoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>
    {{-- <script>
        jQuery.noConflict(true);
    </script> --}}
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
            // const monthSlider = tns({
            //     container: '.trips-month-slider',
            //     nav: false,
            //     controlsContainer: '.trips-month-slider-controls',
            //     autoplay: true,
            //     autoplayButtonOutput: false
            // });


            // For scrollspy functionality
            const tdb = document.querySelector('.tdb')
            if (tdb) {
                const sections = document.querySelectorAll('.tds')
                const sectionScrollObserver = new IntersectionObserver((entries, observer) => {
                    if (entries) {
                        entries.forEach(entry => {
                            const link = tdb.querySelector(`[href="#${entry.target.id}"]`)
                            if (link != null) {
                                if (entry.isIntersecting) {
                                    link.classList.add('bg-accent')
                                } else {
                                    link.classList.remove('bg-accent')
                                }
                            }
                        })
                    }
                }, {
                    rootMargin: "-19% 0px -80% 0px"
                })
                sections.forEach(section => {
                    sectionScrollObserver.observe(section)
                })
            }

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
            $('#ex1').on($.modal.OPEN, function(event, modal) {
                setTimeout(function() {
                    $('.map-image-modal').attr('src', "{{ $mapImageUrl }}");
                    $('.map-image-modal').show();
                    wheelzoom($('.map-image-modal'));
                }, 500);
            });

            $('#ex1').on($.modal.AFTER_CLOSE, function(event, modal) {
                $('.map-image-modal').attr('src', "");
                $('.map-image-modal').hide();
                $('.map-image-modal').trigger('wheelzoom.reset');
            });
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
    <script>
        $(function() {
            let groupDepartureStatus = true;
            let privateDepartureList = [];
            let groupDepartureList = [];
            $(".select-date-departure").on('click', function(event) {
                const dateStr = $(this).data('date');
                filterDepartureByMonth(dateStr);
                removeDateActive();
                $(this).addClass('departure-date-active bg-primary text-white');
                $(this).removeClass('hover:text-primary');
            });

            function removeDateActive() {
                var parentDiv = document.getElementById('all-dates-block');
                var childDivs = parentDiv.getElementsByTagName('button');
                for (var i = 0; i < childDivs.length; i++) {
                    if (childDivs[i].classList.contains('departure-date-active')) {
                        childDivs[i].classList.remove('departure-date-active', 'bg-primary', 'text-white');
                        childDivs[i].classList.add('hover:text-primary');
                    }
                }
            }
            const trip_departures = @json($trip_departures ?? []);
            const trip = @json($trip);

            $("#group-departure").on('click', function(event) {
                document.getElementById("private-departure").classList.remove('text-primary', 'border-primary');
                document.getElementById("group-departure").classList.add('text-primary', 'border-primary');
                showGroupDeparture();
                groupDepartureStatus = true;
            });

            $("#private-departure").on('click', function(event) {
                document.getElementById("group-departure").classList.remove('text-primary', 'border-primary');
                document.getElementById("private-departure").classList.add('text-primary', 'border-primary');
                showPrivateDeparture();
                groupDepartureStatus = false;
            });

            function showGroupDeparture() {
                $('#show-more-departure-button').hide();
                let html = "";
                let filteredDepartures = trip_departures;
                if (filteredDepartures.length > 0) {
                    groupDepartureList = trip_departures;
                    $("#departure-filter-block").html(html);
                    displayMoreGroupDepartureItems(groupDepartureList, 10);
                } else {
                    html = "No departures found.";
                    $("#departure-filter-block").html(html);
                }
            }

            function showPrivateDeparture(month = 1) {
                const trip_days = {!! json_encode($trip->duration) !!};
                const dateList = [];
                let next = true;
                let startDate = convertToTimestamp(`2024-0${month}-01`);
                while (next) {
                    const generateDate = getDateRangeForGap(startDate, parseInt(trip_days));
                    dateList.push(generateDate);
                    startDate = getNextDayTimestamp(generateDate.start);
                    if (!isTimestampInMonth(startDate, month)) {
                        next = false;
                    }
                }
                privateDepartureList = dateList;
                let html = "";
                $("#departure-filter-block").html(html);
                displayMorePrivateDepartureItems(privateDepartureList, 10);
            }

            function displayMoreGroupDepartureItems(items, limit) {
                const itemsContainer = document.getElementById('departure-filter-block');
                // Display the first 'limit' items
                for (let i = 0; i < limit && i < items.length; i++) {
                    const item = items[i];
                    let urlroute = `{{ route('front.trips.departure-booking', ['slug' => 'TRIP_SLUG', 'id' => 'DEPARTURE_ID']) }}`;
                    urlroute = urlroute.replace('TRIP_SLUG', trip.slug);
                    urlroute = urlroute.replace('DEPARTURE_ID', item.id);
                    listItem = `<div class="relative grid grid-cols-2 gap-4 p-4 border border-gray-100 rounded lg:grid-cols-5 lg:place-items-center hover:border-primary">
                            <div class="absolute top-0 px-1 text-xs text-gray-400 bg-white border border-gray-100 rounded-full left-4" style="translate: 0 -50%;">Group</div>
                            <div class="absolute top-0 right-0 w-10 h-10 overflow-hidden rounded">
                                <div class="w-16 px-1 pt-4 text-xs text-center text-white bg-red-600" style="rotate: 45deg; margin-top: -8px">-10%</div>
                            </div>
                            <div>
                                <div class="font-bold">${formatDate(item.from_date)}</div>
                                <div class="text-sm text-gray-400">From ${trip.starting_point}</div>
                            </div>
                            <div>
                                <div class="font-bold">${formatDate(item.to_date)}</div>
                                <div class="text-sm text-gray-400">To ${trip.ending_point}</div>
                            </div>
                            <div>
                                <div class="font-bold">${item.seats}</div>
                                <div class="text-sm text-gray-400">people booked</div>
                            </div>
                            <div>
                                <div class="font-bold">From <span class="text-red"><s>US $ ${numberFormatFromString(trip.cost)}</s></span></div>
                                <div class="text-lg font-bold">US$ ${numberFormatFromString(item.price)}</div>
                                <div class="text-sm"><span class="text-gray-400">Saving </span>US$ ${numberFormatFromString(trip.cost - item.price)}</div>
                            </div>
                            <div class="flex items-center">
                                <a href="${urlroute}" class="px-3 py-2 text-sm border rounded border-primary text-primary hover:bg-primary hover:text-white">Book Now</a>
                            </div>
                        </div>`;
                    $(itemsContainer).append(listItem);
                }

                // If there are more items, add a "Show More" button
                if (items.length > limit) {
                    groupDepartureList = groupDepartureList.slice(limit);
                    $('#show-more-departure-button').show();
                } else {
                    $('#show-more-departure-button').hide();
                }
            }

            function displayMorePrivateDepartureItems(items, limit) {
                const itemsContainer = document.getElementById('departure-filter-block');
                // Display the first 'limit' items
                for (let i = 0; i < limit && i < items.length; i++) {
                    const item = items[i];
                    let urlroute = `{{ route('front.trips.private-departure-booking', ['slug' => 'TRIP_SLUG', 'date' => 'DEPARTURE_DATE']) }}`;
                    urlroute = urlroute.replace('TRIP_SLUG', trip.slug);
                    urlroute = urlroute.replace('DEPARTURE_DATE', item.start);
                    console.log(trip);
                    const listItem = `<div class="relative grid grid-cols-2 gap-4 p-4 border border-gray-100 rounded lg:grid-cols-5 lg:place-items-center hover:border-primary">
                            <div class="absolute top-0 px-1 text-xs text-gray-400 bg-white border border-gray-100 rounded-full left-4" style="translate: 0 -50%;">Private</div>
                            <div class="absolute top-0 right-0 w-10 h-10 overflow-hidden rounded">
                                <div class="w-16 px-1 pt-4 text-xs text-center text-white bg-red-600" style="rotate: 45deg; margin-top: -8px">-10%</div>
                            </div>
                            <div>
                                <div class="font-bold">${convertToFormattedDate(item.start)}</div>
                                <div class="text-sm text-gray-400">From ${trip.starting_point}</div>
                            </div>
                            <div>
                                <div class="font-bold">${convertToFormattedDate(item.end)}</div>
                                <div class="text-sm text-gray-400">To ${trip.ending_point}</div>
                            </div>
                            <div>
                                <div class="text-lg font-bold">US$ ${numberFormatFromString(((trip.offer_price != "")? trip.offer_price: trip.cost))}</div>
                            </div>
                            <div class="flex items-center">
                                <a href="${urlroute}" class="px-3 py-2 text-sm border rounded border-primary text-primary hover:bg-primary hover:text-white">Book Now</a>
                            </div>
                        </div>`;
                    $(itemsContainer).append(listItem);
                }

                // If there are more items, add a "Show More" button
                if (items.length > limit) {
                    privateDepartureList = privateDepartureList.slice(limit);
                    $('#show-more-departure-button').show();
                } else {
                    $('#show-more-departure-button').hide();
                }
            }

            $("#show-more-departure-button").on('click', function(event) {
                if (groupDepartureStatus) {
                    displayMoreGroupDepartureItems(groupDepartureList, 10); // Display the next set of items
                } else {
                    displayMorePrivateDepartureItems(privateDepartureList, 10); // Display the next set of items
                }
            });

            $("#private-departure").click();

            function isTimestampInMonth(timestamp, targetMonth) {
                const date = new Date(timestamp * 1000);
                const month = date.getMonth() + 1; // Adding 1 to match the input targetMonth (1-based)

                return month === targetMonth;
            }

            function getNextDayTimestamp(timestamp) {
                const currentDate = new Date(timestamp * 1000);
                const nextDate = new Date(currentDate);
                nextDate.setDate(currentDate.getDate() + 1);

                const nextDayTimestamp = Math.floor(nextDate.getTime() / 1000);
                return nextDayTimestamp;
            }

            function convertToTimestamp(dateString) {
                const timestamp = Math.floor(Date.parse(dateString) / 1000);
                return timestamp;
            }

            function convertToFormattedDate(timestamp) {
                const date = new Date(timestamp * 1000); // Convert timestamp to milliseconds
                const options = {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric'
                };
                return date.toLocaleDateString('en-US', options);
            }

            function getDateRangeForGap(startTimestamp, gap) {
                const startDateObj = new Date(startTimestamp * 1000);
                const endDateObj = new Date(startDateObj.getFullYear(), startDateObj.getMonth(), startDateObj.getDate() + gap - 1);

                const startTimestampResult = Math.floor(startDateObj.getTime() / 1000);
                const endTimestampResult = Math.floor(endDateObj.getTime() / 1000);

                return {
                    start: startTimestampResult,
                    end: endTimestampResult
                };
            }

            $("#all-departure-filter").on('click', function(event) {
                filterDepartureByMonth("all");
                removeDateActive();
                $(this).addClass('departure-date-active');
            });

            function filterDepartureByMonth(dateStr) {
                let html = "";

                let filteredDepartures = trip_departures;
                // Get the month from the startTimestamp
                if (groupDepartureStatus) {
                    if (dateStr !== "all") {
                        const startMonth = new Date(dateStr * 1000).getMonth() + 1; // Adding 1 because months are zero-based
                        // Filter the array based on the start date in PHP strtotime format
                        filteredDepartures = trip_departures.filter(departure => {
                            const departureMonth = new Date(departure.from_date.replace(/-/g, '/')).getMonth() + 1; // Adding 1 because months are zero-based
                            return departureMonth === startMonth
                        });
                    }
                    if (filteredDepartures.length > 0) {
                        $.each(filteredDepartures, (i, departure) => {
                            let urlroute = "{{ route('front.trips.departure-booking', ['slug' => 'TRIP_SLUG', 'id' => 'DEPARTURE_ID']) }}";
                            urlroute = urlroute.replace('TRIP_SLUG', trip.slug);
                            urlroute = urlroute.replace('DEPARTURE_ID', departure.id);
                            html += `<div class="relative grid grid-cols-2 gap-4 p-4 border border-gray-100 rounded lg:grid-cols-5 lg:place-items-center hover:border-primary">
                                <div class="absolute top-0 px-1 text-xs text-gray-400 bg-white border border-gray-100 rounded-full left-4" style="translate: 0 -50%;">Group</div>
                                <div class="absolute top-0 right-0 w-10 h-10 overflow-hidden rounded">
                                    <div class="w-16 px-1 pt-4 text-xs text-center text-white bg-red-600" style="rotate: 45deg; margin-top: -8px">-10%</div>
                                </div>
                                <div>
                                    <div class="font-bold">${formatDate(departure.from_date)}</div>
                                    <div class="text-sm text-gray-400">From ${trip.starting_point}</div>
                                </div>
                                <div>
                                    <div class="font-bold">${formatDate(departure.to_date)}</div>
                                    <div class="text-sm text-gray-400">To ${trip.ending_point}</div>
                                </div>
                                <div>
                                    <div class="font-bold">${departure.seats}</div>
                                    <div class="text-sm text-gray-400">people booked</div>
                                </div>
                                <div>
                                    <div class="font-bold">From <span class="text-red"><s>US $ ${numberFormatFromString(trip.cost)}</s></span></div>
                                    <div class="text-lg font-bold">US$ ${numberFormatFromString(departure.price)}</div>
                                    <div class="text-sm"><span class="text-gray-400">Saving </span>US$ ${numberFormatFromString(trip.cost - departure.price)}</div>
                                </div>
                                <div class="flex items-center">
                                    <a href="${urlroute}" class="px-3 py-2 text-sm border rounded border-primary text-primary hover:bg-primary hover:text-white">Book Now</a>
                                </div>
                            </div>`;
                        })
                    } else {
                        html = "No departures found.";
                    }
                    console.log(filteredDepartures);
                    $("#departure-filter-block").html(html);
                } else {
                    // private
                    let startMonth = 1;
                    if (dateStr !== "all") {
                        startMonth = new Date(dateStr * 1000).getMonth() + 1;
                    }
                    showPrivateDeparture(startMonth);
                }
            }

            function formatDate(date) {
                return new Date(date.replace(/-/g, '/')).toLocaleDateString('en-GB', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });
            }

            function numberFormatFromString(price) {
                return parseInt(price, 10).toLocaleString();
            }
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
    </script>
@endpush
