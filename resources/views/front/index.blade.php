@extends('layouts.front')
@section('content')
    <!-- Slider -->
    @include('front.elements.banner2')

    {{-- Activities --}}
    <div class="py-20 activities">
        <div class="container">
            <div>
                <p class="mb-2 text-2xl text-center font-handwriting text-primary">Choose your activity</p>

                <div class="flex justify-center">
                    <h2 class="relative pl-10 pr-10 mb-8 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">
                        Things To Do
                        <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    </h2>
                </div>
            </div>

            <div class="grid flex-wrap grid-cols-2 gap-10 md:flex lg:justify-center">
                @foreach ($activities as $activity)
                    @include('front.elements.activity-card', ['activity' => $activity])
                @endforeach
            </div>
        </div>
    </div>{{-- Activities --}}

    <!-- About and video-->
    <div class="py-20 bg-gray">
        <div class="container">
            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <div class="mb-4">
                        <p class="mb-2 text-2xl text-center font-handwriting text-primary">About Us</p>

                        <div class="flex justify-center mb-8">
                            <h2 class="relative px-10 text-2xl font-bold text-center text-gray-600 uppercase font-display">
                                {{ Setting::get('homePage')['welcome']['title'] ?? '' }}
                                <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                                <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                            </h2>
                        </div>
                    </div>

                    <div class="relative prose" x-data="{ isExpanded: false }">
                        <div class="pb-20" x-show="isExpanded" x-collapse.min.300px>
                            <?= Setting::get('homePage')['welcome']['content'] ?? '' ?>
                        </div>
                        <div class="absolute bottom-0 flex justify-center w-full py-4" style="background: linear-gradient(to top, rgba(240,240,240,1), rgba(240,240,240,0));">
                            <button class="px-4 py-2 text-xs rounded-full bg-light" x-on:click="isExpanded=!isExpanded" x-text="isExpanded?'Show less':'Show more'">
                                Show more
                            </button>
                        </div>
                    </div>

                </div>
                <div>
                    <a data-fancybox class="relative block lg:p-10" href="https://www.youtube.com/watch?v=d9elHMRK294&amp;autoplay=1&amp;rel=0&amp;controls=1&amp;showinfo=0">
                        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="" class="rounded-3xl">
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
                @push('styles')
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css">
                @endpush
                @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
                @endpush
            </div>
        </div>
    </div>
    <!-- About -->

    <!-- Popular right now -->
    <div class="py-20 featured">
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
        </div>
    </div> <!-- Popular right now -->

    {{-- Reviews --}}
    <div class="py-20 bg-light">
        <div class="container">
            <div class="flex justify-center">
                <div>
                    <p class="mb-2 text-2xl text-center text-primary font-handwriting">Hear it from our satisfied customers</p>

                    <h2 class="relative px-10 mb-16 text-3xl font-bold text-center text-gray-600 uppercase lg:text-5xl font-display">
                        Customer reviews
                        <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    </h2>
                </div>
            </div>
            <div class="grid gap-6 mb-4 lg:grid-cols-2">
                @foreach ($reviews as $review)
                    <div class="p-6 bg-white rounded-lg review">
                        <div class="prose">
                            <h2 class="mb-4 text-2xl font-display text-primary">{{ $review->title }}</h2>
                            <p>{{ $review->review }}</p>

                        </div>
                        <div class="flex items-center justify-between mt-6">
                            <div class="flex items-center gap-4">
                                <div>
                                    <img src="{{ $review->thumbImageUrl }}" alt="">
                                </div>
                                <div>
                                    <div class="font-bold">{{ ucfirst($review->review_name) }}</div>
                                    <div class="text-sm text-gray">{{ $review->review_country }}</div>
                                    <div class="flex">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="w-4 h-4 text-accent">
                                                <use xlink:href="{{ asset('assets/front/img/sprite.svg') }}#star" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="w-10 h-10 text-light" viewBox="0 0 16 16">
                                <path
                                    d="M16 8c0 3.866-3.582 7-8 7a9.06 9.06 0 0 1-2.347-.306c-.584.296-1.925.864-4.181 1.234-.2.032-.352-.176-.273-.362.354-.836.674-1.95.77-2.966C.744 11.37 0 9.76 0 8c0-3.866 3.582-7 8-7s8 3.134 8 7zM7.194 6.766a1.688 1.688 0 0 0-.227-.272 1.467 1.467 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 5.734 6C4.776 6 4 6.746 4 7.667c0 .92.776 1.666 1.734 1.666.343 0 .662-.095.931-.26-.137.389-.39.804-.81 1.22a.405.405 0 0 0 .011.59c.173.16.447.155.614-.01 1.334-1.329 1.37-2.758.941-3.706a2.461 2.461 0 0 0-.227-.4zM11 9.073c-.136.389-.39.804-.81 1.22a.405.405 0 0 0 .012.59c.172.16.446.155.613-.01 1.334-1.329 1.37-2.758.942-3.706a2.466 2.466 0 0 0-.228-.4 1.686 1.686 0 0 0-.227-.273 1.466 1.466 0 0 0-.469-.324l-.008-.004A1.785 1.785 0 0 0 10.07 6c-.957 0-1.734.746-1.734 1.667 0 .92.777 1.666 1.734 1.666.343 0 .662-.095.931-.26z" />
                            </svg>
                        </div>
                    </div>
                @endforeach

            </div>
            <div class="text-center">
                <a href="{{ route('front.reviews.index') }}" class="btn btn-sm btn-primary">
                    View all reviews
                </a>
            </div>
        </div>
    </div> {{-- Reviews --}}

    {{-- <!-- Destinations -->
    <div class="py-20 destinations">
        <div class="container">
            <div class="mb-4">
                <p class="mb-2 text-2xl text-center font-handwriting text-primary">Where do you want to go?</p>

                <div class="flex justify-center mb-10">
                    <h2 class="relative px-10 text-3xl font-bold text-gray-600 uppercase lg:text-5xl font-display">
                        AWESOME DESTINATIONS
                        <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                        <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    </h2>
                </div>
            </div>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                @forelse ($destinations as $destination)
                    @include('front.elements.destination_card', ['destination' => $destination])
                @empty
                @endforelse
            </div>
        </div>
    </div><!-- Destinations --> --}}

    <!-- Trip of the month -->
    <div class="py-20 text-white bg-primary">
        <div class="container">

            <p class="mb-2 text-2xl text-white font-handwriting">This doesn't get any better</p>

            <div class="flex">
                <h2 class="relative pr-10 mb-10 text-3xl font-bold uppercase lg:text-5xl font-display">
                    {{ Setting::get('homePage')['trip_block_3']['title'] ?? '' }}
                    <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                </h2>
            </div>

            <div class="flex justify-end gap-4 trips-month-slider-controls">
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
    </div>

    {{--
    <!-- Departure Dates -->
    <div class="py-20 departure-dates">
        <div class="container">
            <div class="items-center justify-between mb-4 lg:flex">
                <div>
                    <h1 class="text-4xl uppercase lg:text-5xl font-display text-primary">Upcoming Departures
                    </h1>
                    <div class="mb-6 underline bg-accent"></div>
                </div>

                <form id="filter-trip-departure-form" action="" method="GET">
                    <div class="form-group">
                        <select name="month" id="select-trip-departure-filter" class="bg-gray">
                            <option selected disabled>Choose Month & Year</option>
                            @php
                                $current_date = \Carbon\Carbon::now();
                            @endphp
                            <option value="{{ $current_date->format('n') }}">{{ $current_date->format('M Y') }}</option>
                            @for ($i = 0; $i < 3; $i++)
                                @php
                                    $current_date->add('1 month')->format('M Y');
                                @endphp
                                <option value="{{ $current_date->format('n') }}">{{ $current_date->format('M Y') }}</option>
                            @endfor
                        </select>
                    </div>
                </form>
            </div>
            <div id="departure-card-block" class="grid grid-cols-2 gap-2 md:grid-cols-3 lg:grid-cols-5">
                @forelse ($departures as $departure)
                    @include('front.elements.tour_departure_card', $departure)
                @empty
                @endforelse
            </div>
        </div>
    </div><!-- Departure Dates -->

--}}

    {{-- Why Travel with us --}}
    {{--
    <div class="py-20 text-white bg-primary">
        <div class="container grid lg:grid-cols-3 ">
            <div class="col-span-2 p-10">
                <h1 class="mb-2 text-4xl text-white uppercase lg:text-5xl font-display">Why Travel With Us</h1>
                <div class="mb-6 underline bg-accent"></div>
                <ul class="list-style columns">
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>25 years of experience in the trekking industry
                    </li>
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Affordable price on all tour trips without cutting corners in our excellent services.</li>
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Dedicated, hardworking qualified guides and staff.</li>
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Our company offers customizable itineraries, allowing clients to tailor their trekking experience to their interests, fitness level and schedule</li>
                 
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Providing top-notch equipment and gear for all trekkers ensures the safety and comfort of our clients on their journey.</li>
                    <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Providing full information and details of every trip chosen from our brochure or websites.</li>
                      <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Good-condition vehicles for easy and smooth transportation according to the size of a group.</li>
                      <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Using the best hotels in Kathmandu, Pokhara, and Nepalgunj, including local lodges on treks as per the itineraries.</li>
                      <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>The Company takes good care, of the welfare of staff, guides, and porters with good wages, health / medical care, and insurance for all field staff on all our trips.</li>
                      <li class="flex mb-2"><svg class="flex-shrink-0 w-6 h-6 mr-2 text-accent" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>Providing 24/7 support for clients during their trek</li>
                </ul>
            </div>
            <div class="p-10">
               
            </div>
        </div>
    </div>
    --}}
    {{-- Why Travel with us --}}

    <!-- Blog -->
    <div class="py-20 blog bg-gray">
        <div class="container">

            <div class="flex justify-center">
                <h2 class="relative px-10 mb-16 text-3xl font-bold text-center text-gray-600 uppercase lg:text-5xl font-display">
                    Latest travel blog
                    <div class="absolute left-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                    <div class="absolute right-0 w-6 h-1 rounded top-1/2 bg-accent"></div>
                </h2>
            </div>

            <div class="grid gap-2 lg:grid-cols-3 lg:gap-6">
                @forelse ($blogs as $blog)
                    <a href="{{ route('front.blogs.show', $blog->slug) }}">
                        <div>
                            <div class="mb-4">
                                <img src="{{ $blog->imageUrl }}" alt="" class="rounded-lg">
                            </div>
                            <div class="content">
                                <h2 class="mb-2 text-xl">{{ $blog->name }}</h2>
                                <div class="flex items-center mb-2 text-sm text-gray-600"><svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ formatDate($blog->blog_date) }}
                                </div>
                                <p class="text-sm">
                                    {{ truncate(strip_tags($blog->description)) }}
                                </p>

                            </div>
                        </div>
                    </a>

                @empty
                @endforelse
            </div>
            <a href="https://www.havenholidaysnepal.com/blogs" class="flex items-center gap-2 text-primary">Go to blog
                <svg class="w-4 h-4">
                    <use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" />
                </svg>
            </a>
        </div>
    </div><!-- Blog -->

    @include('front.elements.plan_trip')

    {{-- @include('front.elements.search_widget') --}}
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

            const monthSlider = tns({
                container: '.trips-month-slider',
                nav: false,
                controlsContainer: '.trips-month-slider-controls',
                autoplay: true,
                autoplayButtonOutput: false
            })
        });
    </script>
@endpush
