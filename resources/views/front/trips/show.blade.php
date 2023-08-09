<?php
$mapImageUrl = $trip->mapImageUrl;
$page_trip_id = $trip->id;
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

        <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "@yield('meta_og_title')",
      "image": [
        "@yield('meta_og_url')" ],
      "description": "@yield('meta_description')",
      "sku": "World Himalaya",
      "mpn": "World Himalaya",
      "brand": {
        "@type": "Brand",
        "name": "World Himalaya"
      },
      "review": {
        "@type": "Review",
        "reviewRating": {
          "@type": "Rating",
          "ratingValue": "4",
          "bestRating": "5"
        },
        "author": {
          "@type": "Person",
          "name": "World Himalaya"
        }
      },
      "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "5",
        "reviewCount": "89"
      },
      "offers": {
        "@type": "Offer",
        "url": "{{ route('front.trips.show', ['slug' => $trip->slug]) }}",
        "priceCurrency": "USD",
        "price": "{{ ($trip->offer_price) }}",
        "priceValidUntil": "2030-11-20",
        "itemCondition": "https://schema.org/UsedCondition",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

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
            .hero-slider > :not(:first-child){
                display:none;
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
                    <img class="lazy" src="{{ $gallery->imageUrl }}" class="block" alt="{{ $trip->name }}">
                @endforeach
            @endif
        </div>

        <div class="absolute w-full overlay">
            <div class="container flex flex-wrap items-end justify-between">
                <div>
                    <div class="hidden hero-slider-controls md:block">
                        <div class="flex gap-2">
                            <button>
                                <svg class="w-6 h-6">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowleft" />
                                </svg>
                            </button>
                            <button>
                                <svg class="w-6 h-6">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="mt-8 text-2xl text-white">{{ $trip->duration }} days</div>
                    <h1 class="my-4 text-4xl font-bold text-white uppercase font-display hero-slider-title">
                        <span>{{ $trip->name }} </span>
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
            </div>
        </div>

    </section>
    <section class="bg-gray-100">
        <div id="navIO"></div>
        <!-- Sticky Nav -->
        <div class="top-0 text-white tdb bg-primary sticky-top">
            <div class="container flex items-center justify-center">
                <nav class="flex items-center justify-center tour-details-tabs" id="secondnav">
                    <ul class="flex flex-wrap justify-center gap-2 py-1 shadow-md nav">
                        <li class="mr-2">
                            <a href="#overview" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#viewgrid" />
                                </svg>
                                <span class="hidden md:block">Overview</span>
                            </a>
                        </li>
                        @if (!$trip->trip_itineraries->isEmpty())
                            <li class="mr-2">
                                <a href="#itinerary" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#clock" />
                                    </svg>
                                    <span class="hidden md:block">Itinerary</span></a>
                            </li>
                        @endif

                        @if ($trip->trip_include_exclude)
                            <li class="mr-2">
                                <a href="#inclusions" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#archive" />
                                    </svg>
                                    <span class="hidden md:block">Inclusions</span>
                                </a>
                            </li>
                        @endif

                        @if (!$trip->trip_departures->isEmpty())
                            <li class="mr-2">
                                <a href="#date-price" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendar" />
                                    </svg>
                                    <span class="hidden md:block">Date & Price</span></a>
                            </li>
                        @endif



                        @if (!$trip->trip_faqs->isEmpty())
                            <li class="mr-2">
                                <a href="#faqs" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                    <svg class="w-6 h-6 md:mr-2">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#questionmarkcircle" />
                                    </svg>
                                    <span class="hidden md:block">FAQs</span></a>
                            </li>
                        @endif

                        <li class="mr-2">
                            <a href="#reviews" class="flex items-center p-2 hover:bg-white hover:text-primary">
                                <svg class="w-6 h-6 md:mr-2">
                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#chat" />
                                </svg>
                                <span class="hidden md:block">Review</span></a>
                        </li>

                    </ul>
                </nav>
            </div>
            <div id="tourDetailsBarIO"></div>
        </div><!-- Sticky Nav -->

        <div class="container pb-20 mt-2">
            <div class="grid gap-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-10">

                <div class="relative tour-details lg:col-span-2 xl:col-span-3">

                    <div class="lg:hidden">
                        @include('front.elements.price_card')
                    </div>

                    {{-- Overview --}}
                    <div id="overview" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                        <div>

                            <div class="grid gap-6 mb-6 md:grid-cols-2 lg:grid-cols-3">
                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendarduration" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Duration
                                        </div>
                                        <div>
                                            {{ $trip->duration }} days
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#maxelevation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Max. Elevation
                                        </div>
                                        <div>
                                            {{ $trip->max_altitude }}m
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#groupsize" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Group size
                                        </div>
                                        <div>
                                            {{ $trip->group_size }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#level" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Level
                                        </div>
                                        <div>
                                            {{ $trip->difficulty_grade_value }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#transportation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Transportation
                                        </div>
                                        <div>
                                            <?= $trip->trip_info->transportation ?? '' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#bestseason" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Best Season
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->best_season ?? '' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#accomodation" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Accomodation
                                        </div>
                                        <div>
                                            <?= $trip->trip_info->accomodation ?? '' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#meals" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Meals
                                        </div>
                                        <div>
                                            <?= $trip->trip_info->meals ?? '' ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#startsat" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Starts at
                                        </div>
                                        <div>
                                            {{ $trip->starting_point }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex table-item aic">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#endsat" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Ends at
                                        </div>
                                        <div>
                                            {{ $trip->ending_point }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex aic lg:col-span-2">
                                    <div class="flex-shrink-0 mr-4">
                                        <svg class="flex-shrink-0 w-10 h-10 text-primary">
                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#triproute" />
                                        </svg>
                                    </div>

                                    <div>
                                        <div class="text-xs font-bold text-gray">
                                            Trip Route
                                        </div>
                                        <div>
                                            {{ $trip->trip_info->trip_route ?? '' }}
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="prose">
                                <h3 class="mb-2 text-2xl font-display text-primary">Highlights</h3>
                                <div class="mb-4 highlights">
                                    {!! $trip->trip_info ? $trip->trip_info->highlights : '' !!}
                                </div>

                                <div id="overview-text" style="margin-bottom: 15px;">
                                    {!! $trip->trip_info ? $trip->trip_info->overview : '' !!}
                                </div>
                                {{-- <p class="text-center">
                                <button id="toggle-overview" class="btn btn-gray" data-bs-toggle="collapse" data-bs-target="#overview-text">Show
                                    More</button>
                                </p> --}}

                                <div class="px-4 py-1 mb-3 bg-light">
                                    <h3 class="font-display text-primary"> Important Note</h3>
                                    <p class="text-sm">
                                        {!! $trip->trip_info ? $trip->trip_info->important_note : '' !!}
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>{{-- Overview --}}

                    <!--<div class='mb-4 embed-container'><iframe src='https://www.youtube.com/embed//dFLxa0VwY-E' frameborder='0' allowfullscreen></iframe></div>-->

                    {{-- Itinerary --}}
                    <div id="itinerary" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10 scroll-pt-36" x-data="{
                        day1Open: true,
                        @for ($i = 1; $i < $trip->trip_itineraries->count() ; $i++)
                        day{{ $i + 1 }}Open:false, @endfor
                    }">
                        <div class="flex flex-wrap items-end justify-between mb-4">
                            <h2 class="text-4xl font-bold font-display text-primary">Trip Itinerary</h2>
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
                                <div class="mb-2 rounded-lg border-light">
                                    <button class="flex items-center w-full p-2 text-left rounded-t-lg text-primary bg-light" :aria-expanded="day{{ $i + 1 }}Open"
                                        aria-controls="day{{ $i + 1 }}" @click="day{{ $i + 1 }}Open=!day{{ $i + 1 }}Open">
                                        <div class="items-center gap-2 mr-4 lg:flex">
                                            <div class="text-sm">Day</div>
                                            <div class="text-2xl font-display text-primary">
                                                {{ $itinerary->day }}
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between flex-grow gap-4 lg:gap-10">
                                            <h3 class="font-bold text-gray-600 lg:text-lg font-display">{{ $itinerary->name }}</h3>
                                            <div class="flex-shrink-0 text-white rounded-lg bg-primary ">
                                                <svg class="w-4 h-4 lg:w-6 lg:h-6" x-cloak x-show="!day{{ $i + 1 }}Open">
                                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#plus" />
                                                </svg>
                                                <svg class="w-4 h-4 lg:w-6 lg:h-6" x-cloak x-show="day{{ $i + 1 }}Open">
                                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#minus" />
                                                </svg>
                                            </div>
                                        </div>
                                    </button>
                                    <div id="day{{ $i + 1 }}" x-cloak x-show="day{{ $i + 1 }}Open" x-collapse>
                                        <div class="py-4">
                                            {{-- <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="{{ $trip->name }}"> --}}
                                            <div class="prose">
                                                <p>
                                                    {!! $itinerary->description !!}
                                                </p>
                                            </div>
                                        </div>
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
                    </div>{{-- Itinerary --}}

                    {{-- Includes / Excludes --}}
                    <div id="inclusions" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                        <div class="">
                            @if ($trip->trip_include_exclude)
                                <div class="grid gap-10 lg:grid-cols-2">
                                    <div>
                                        <h2 class="mb-4 text-2xl font-bold font-display text-primary">Includes</h2>
                                        <div class="prose includes">
                                            <?= $trip->trip_include_exclude->include ?>
                                        </div>
                                    </div>

                                    <div>
                                        <h2 class="mb-4 text-2xl font-bold font-display text-primary">Doesn't Include</h2>
                                        <div class="prose excludes">
                                            <?= $trip->trip_include_exclude->exclude ?>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="p-4 mt-4 bg-gray-100 rounded-xl">
                                <h2 class="mb-4 text-2xl font-bold font-display text-primary">Equipment Checklist</h2>
                                <div class="prose complimentary">
                                    <?= $trip->trip_include_exclude->complimentary ?? '' ?>
                                </div>
                            </div>
                        </div>
                    </div>{{-- Includes / Excludes --}}

                    {{-- Departures --}}
                    @if (!$trip->trip_departures->isEmpty())
                        <div id="date-price" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                            <div class="bg-white ">
                                <div class="items-center justify-between mb-4 lg:flex">
                                    <h2 class="text-4xl font-bold font-display text-primary">Upcoming Departures
                                    </h2>

                                    <form id="filter-trip-departure-form" action="" method="GET">
                                        <select name="month" id="select-trip-departure-filter" class="bg-gray-100 rounded-full">
                                            <option selected disabled>Choose Month & Year</option>
                                            @php
                                                $current_date = \Carbon\Carbon::now();
                                            @endphp
                                            <option value="{{ $current_date->format('n') }}">{{ $current_date->format('M Y') }}</option>
                                            @for ($i = 0; $i < 24; $i++)
                                                @php
                                                    $current_date->add('1 month')->format('M Y');
                                                @endphp
                                                <option value="{{ $current_date->format('n') }}">{{ $current_date->format('M Y') }}</option>
                                            @endfor
                                        </select>
                                    </form>
                                </div>

                                {{--<div id="departure-card-block" class="grid gap-4 md:grid-cols-2">
                                    @foreach ($trip->trip_departures as $departure)
                                        <div class="p-4 border border-primary lg:p-10">

                                            <div class="text-sm">
                                                <div class="flex mb-2 aic">
                                                    <svg class="w-4 h-4 mr-1 text-primary">
                                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#calendar" />
                                                    </svg>
                                                    {{ formatDate($departure->from_date) }} to {{ formatDate($departure->to_date) }}
                                                </div>
                                                <div class="flex mb-2 aic">
                                                    <svg class="w-4 h-4 mr-1 text-primary">
                                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#tag" />
                                                    </svg>
                                                    <div>
                                                        <small class="text-gray"><s>USD {{ number_format($departure->trip->cost) }}</s></small><br>
                                                        <span class="text-green">USD <b>{{ number_format($departure->price) }}</b></span>
                                                    </div>
                                                </div>
                                                <div class="flex mb-8">
                                                    <svg class="w-4 h-4 mr-1 text-primary">
                                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#users" />
                                                    </svg>
                                                    {{ $departure->seats }} seats left
                                                </div>
                                            </div>
                                            <div><a href="{{ route('front.trips.departure-booking', ['slug' => $departure->trip->slug, 'id' => $departure->id]) }}" class="btn btn-sm btn-accent">Join
                                                    Group</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>--}}
                                <table class="table mb-2">
                                    <thead>
                                        <th class="upper text-left">{{ $trip->name }}</th>
                                        <th class="upper text-left">Price</th>
                                        <th class="upper text-left">Seats Left</th>
                                        <th></th>
                                    </thead>
                                    <tbody>
                                        @foreach($trip->trip_departures as $departure)
                                            <tr>
                                                <td>
                                                    <div class="flex items-center">
                                                        <svg class="icon mr-1 text-primary">
                                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#calendar') }}" />
                                                        </svg>
                                                        {{ formatDate($departure->from_date) }} — {{ formatDate($departure->to_date) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex items-center">
                                                        <svg class="icon mr-1 text-primary">
                                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#tag') }}" />
                                                        </svg>
                                                        <div>
                                                            <small class="text-gray"><s>USD {{ number_format($trip->cost) }}</s></small><br>
                                                            <span class="text-green">USD <b>{{ number_format($departure->price) }}</b></span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="flex items-center">
                                                        <svg class="icon mr-1 text-primary">
                                                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#users') }}" />
                                                        </svg>
                                                        {{ $departure->seats }}
                                                    </div>
                                                </td>
                                                <td><a href="{{ route('front.trips.departure-booking', ['slug' => $trip->slug, 'id' => $departure->id]) }}" class="btn btn-sm btn-accent">Join Group</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- <p class="text-center"><button id="more-dates" class="btn btn-sm btn-gray">See more dates</button></p> -->

                            </div>
                        </div>
                    @endif{{-- Departures --}}

                    {{-- <div class="mb-4">
                    <iframe src="https://www.google.com/maps/d/embed?mid=1o2LaX1o68hVBiycWHWDrK_F18H1epiGB" width="100%" height="480" class="border-none"></iframe>
                </div> --}}

                    {{-- FAQs --}}
                    @if (!$trip->trip_faqs->isEmpty())
                        <div id="faqs" class="px-4 pt-10 pb-4 mb-4 bg-white tds lg:px-10">
                            <h2 class="mb-4 text-4xl font-bold font-display text-primary">Frequently Asked Questions</h2>

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
                        </div>
                    @endif{{-- FAQs --}}

                    {{-- Reviews --}}
                    <div id="reviews" class="px-4 py-10 mb-4 bg-white tds lg:px-10">
                        <div class="items-center justify-between mb-4 lg:flex">
                            <h2 class="text-4xl font-bold font-display text-primary">Reviews
                            </h2>
                            <div>
                                <a href="{{ route('front.reviews.create') }}" class="mr-1 btn btn-primary btn-sm" data-toggle="modal" data-target="#review-modal">
                                    Write a review</a>
                            </div>
                        </div>
                        <div class="grid gap-10">
                            @if (iterator_count($trip->trip_reviews))
                                @foreach ($trip->trip_reviews()->limit(5)->where('status', 1)->get() as $review)
                                    <div class="review">
                                        <h2 class="mb-2 text-xl text-gray-600 font-display font-bold">{{ $review->title }}</h2>
                                        <div class="prose review__body">
                                            <p>{{ $review->review }}</p>
                                        </div>
                                        <div class="flex items-center mt-4">
                                            <div class="mr-2">
                                                <img class="lazy" src="{{ $review->thumbImageUrl }}" alt="{{ $trip->name }}">
                                            </div>
                                            <div>
                                                <div class="font-bold">{{ $review->review_name }}</div>
                                                <div class="text-sm text-gray">{{ $review->review_country }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <a href="{{ route('front.reviews.index') }}" class="inline-flex items-center gap-2 mt-4 text-primary">See more reviews
                            <svg class="w-4 h-4">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#arrownarrowright" />
                            </svg>
                        </a>
                    </div>{{-- Reviews --}}

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
                            <a href="{{ route('front.trips.print', ['slug' => $trip->slug]) }}" class="flex items-center gap-2" title="Print tour details">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6" viewBox="0 0 16 16">
                                    <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z" />
                                    <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z" />
                                </svg>
                                <span class="font-display">Print Tour Details</span>
                            </a>
                        </div>
                    </div>

                    <div>
                        <h2 class="mb-2 font-bold text-gray-400 font-display">Share this tour</h2>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('front.trips.show', ['slug' => $trip->slug]) }}" class="mr-2 text-primary hover:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ route('front.trips.show', ['slug' => $trip->slug]) }}&text=" class="mr-2 text-primary hover:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6" viewBox="0 0 16 16">
                                    <path
                                        d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                </svg>
                            </a>
                            <a href="{{ Setting::get('instagram') }}" class="text-primary hover:text-accent">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-6 h-6" viewBox="0 0 16 16">
                                    <path
                                        d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                {{-- aside --}}
                <aside class="pt-10">

                    @include('front.elements.price_card'){{--
                <a href="{{ route('front.trips.booking', $trip->slug) }}" class="w-full mb-8 btn btn-accent">Ask for agency price</a> --}}



                    @include('front.elements.enquiry')

                    <!-- Route Map -->
                    @if ($trip->map_file_name)
                        <div class="mb-8">
                            <div class="card-header">
                                <h2 class="mb-2 text-2xl uppercase font-display text-primary">Route Map</h2>
                            </div>
                            <div class="p-0 card-body">
                                <a data-fancybox data-caption="{{ $trip->name }} Map" href="{{ $trip->mapImageUrl }}">
                                    <img class="lazy" class="img-fluid" src="{{ $trip->mapImageUrl }}" alt="{{ $trip->name }}">
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
                            <a href="tel:{{ Setting::get('mobile1') }}" class="flex aic">
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

                    <div class="flex justify-between px-4 py-2 mb-8 bg-light">
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
                            <h2 class="mb-2 text-2xl font-bold font-display text-primary">Additional Tours</h2>
                            @forelse ($trip->addon_trips as $addon_trip)
                                @include('front.elements.addon_trip', ['trip' => $addon_trip])
                            @empty
                            @endforelse
                        </div>
                    @endif



                    <div class="sticky-top sticky-price none lg:block" style="top: 5rem; z-index:0">
                        @include('front.elements.price_card')
                    </div>

                </aside>
            </div>
        </div>

        <!-- Similar -->
        @if (!$trip->similar_trips->isEmpty())
            <div class="pb-20 bg-gray-100">
                <div class="container">
                    <h2 class="mb-8 text-4xl font-bold font-display text-primary">Similar Tours</h2>
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



@endsection
@push('scripts')
    <!--<script src="{{ asset('assets/front/js/tour-details.js') }}"></script>-->
    <script src="https://cdn.jsdelivr.net/npm/wheelzoom@4.0.1/wheelzoom.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tiny-slider@2.9.3/dist/tiny-slider.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
    <script>
        Fancybox.bind("[data-fancybox]", {
          // Your custom options
        });
    </script>
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

            // show/hide header
            // const header = document.querySelector('.header')
            // const navIO = document.querySelector('#navIO')
            // console.log(header)
            // const navObserver = new IntersectionObserver((entries, observer) => {
            //     console.log(entries[0].isIntersecting)
            //     if (!entry[0].isIntersecting) {
            //         header.classList.remove('flex')
            //         header.classList.add('none')
            //     } else {
            //         header.classList.remove('none')
            //         header.classList.add('flex')
            //     }
            // }, {
            //     rootMargin:"0px 0px 0px 0px"
            // })
            // navObserver.observe(navIO)

            // For scrollspy functionality
            const tdb = document.querySelector('.tdb')
            const sections = document.querySelectorAll('.tds')
            const sectionScrollObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    const link = tdb.querySelector(`[href="#${entry.target.id}"]`)
                    if (entry.isIntersecting) {
                        link.classList.add('bg-accent', 'text-dark')
                    } else {
                        link.classList.remove('bg-accent', 'text-dark')
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
                    let img = '<img class="img-fluid map-image-modal" src="{{ $mapImageUrl }}" alt="{{ $trip->name }}">';
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
    <script>
        $(function() {
            $("#select-trip-departure-filter").on('change', function(event) {
                event.preventDefault();
                let url = "{!! route('front.trip-departures.filter') !!}";
                let e = $(this);
                let month = e.children("option:selected").val();
                let trip_id = "{!! $page_trip_id !!}";

                $.ajax({
                    url: url,
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        month: month,
                        trip_id: trip_id
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

            // const activitiesSlider = tns({
            //     container: '.activities-slider',
            //     nav: false,
            //     controlsContainer: '.activities-slider-controls',
            //     items: 2,
            //     gutter: 16,
            //     rewind: true,
            //     responsive: {
            //         768: {
            //             items: 3
            //         },
            //         992: {
            //             items: 5
            //         }
            //     }
            // })

            // const monthSlider = tns({
            //     container: '.trips-month-slider',
            //     nav: false,
            //     controlsContainer: '.trips-month-slider-controls',
            //     autoplay: true,
            //     autoplayButtonOutput: false
            // })
        });
    </script>
@endpush
