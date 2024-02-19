<?php
if (session()->has('success_message')) {
    $session_success_message = session('success_message');
    session()->forget('success_message');
}

if (session()->has('error_message')) {
    $session_error_message = session('error_message');
    session()->forget('error_message');
}
$all_selected_destinations = '';

if (isset($selected_destinations) && !empty($selected_destinations)) {
    $all_selected_destinations = $selected_destinations;
}

$selected_trip_id = '';
$selected_trip_name = '';
if (isset($trip) && !empty($trip)) {
    $selected_trip_id = $trip->id;
    $selected_trip_name = $trip->name;
}
?>

@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/front/css/front-search-slider.css') }}">
    <style>
        .step {
            flex-basis: 100px;
        }

        .step:not(:first-child)::before {
            content: '';
            position: absolute;
            top: 1.3rem;
            right: 50%;
            width: 100%;
            height: .5rem;
            background-color: var(--light);
            z-index: -1;
        }

        .step.active:not(:first-child)::before {
            background-color: var(--accent);
        }

        .step .step-bg {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 3rem;
            height: 3rem;
            background-color: var(--light);
            border-radius: 100%;
        }

        .step.active .step-bg {
            background-color: var(--primary);
        }

        .step.active img {
            filter: brightness(10);
        }

        .radio-input,
        .radio-input-compact,
        .check-input {
            opacity: 0;
            position: absolute;
        }

        .radio-input+label {
            position: relative;
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 2rem 1rem;
            background-color: #f0f8ff;
            cursor: pointer;
            border-radius: 1rem;
        }

        .radio-input+label.col {
            gap: .5rem;
            flex-direction: column;
        }

        .radio-input+label:hover {
            background-color: var(--accent);
        }

        .radio-input:checked+label {
            background-color: var(--primary);
            color: white;
        }

        .radio-input:checked+label img {
            filter: brightness(6);
        }

        .radio-input-compact+label {
            position: relative;
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            background-color: var(--light);
            cursor: pointer;
            border-radius: 0.5rem;
        }

        .radio-input-compact+label svg {
            color: var(--primary);
        }

        .radio-input-compact+label.col {
            gap: .5rem;
            flex-direction: column;
        }

        .radio-input-compact+label:hover {
            background-color: var(--accent);
        }

        .radio-input-compact:checked+label {
            background-color: var(--primary);
            color: white;
        }

        .radio-input-compact+label .check {
            fill: var(--primary);
            opacity: 0;
        }

        .radio-input-compact:checked+label .check {
            opacity: 1;
        }

        .check-input+label {
            position: relative;
            display: flex;
            gap: 1rem;
            align-items: center;
            padding: 1rem;
            background-color: var(--light);
            cursor: pointer;
            border-radius: 0.5rem;
        }

        .check-input+label.col {
            gap: .5rem;
            flex-direction: column;
        }

        .check-input+label:hover {
            background-color: var(--light);
        }

        .check-input:checked+label {
            background-color: var(--primary);
            color: white;
        }

        .check-input+label .check {
            fill: var(--primary);
            opacity: 0;
        }

        .check-input:checked+label .check {
            opacity: 1;
        }

        #stepForm>div {
            display: none;
        }

        #stepForm>div:first-of-type {
            display: block;
        }

        .ui-slider-range {
            background-color: var(--primary);
        }
    </style>
@endpush

@extends('layouts.front_inner')

@section('content')
    <!-- Hero -->
    <section class="relative hero-alt" style="min-height: 200px;">
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="" style="max-height: 400px;">
        <div class="absolute overlay">
            <div class="container ">
                <h1>Plan My Trip</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Plan My Trip</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="py-20" x-data="">
        <div class="max-w-2xl mx-auto px-4 grid gap-20">
            {{-- Progress --}}
            <div id="step-block" class="hidden lg:flex">
                {{-- Mark each step as active if it is complete or current --}}
                <button id="step-who" class="step active relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/couple.svg') }}" class="w-8 h-8 object-contain"></div>Who
                </button>
                <button id="step-when" class="step relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/when.svg') }}" class="w-8 h-8 object-contain"></div>Date
                </button>
                <button id="step-where" class="step relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/where.svg') }}" class="w-8 h-8 object-contain"></div>Where
                </button>
                <button id="step-accomodation" class="step relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/accommodation.svg') }}" class="w-8 h-8 object-contain"></div>Accomodation
                </button>
                <button id="step-budget" class="step relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/budget.svg') }}" class="w-8 h-8 object-contain"></div>Budget
                </button>
                <button id="step-tailor-made" class="step relative flex-grow flex gap-2 flex-col items-center text-gray-600 ">
                    <div class="step-bg"><img src="{{ asset('assets/front/img/tailor-made.svg') }}" class="w-8 h-8 object-contain"></div>Customize
                </button>
            </div>
        </div>

        <div class="max-w-4xl mx-auto px-4 py-20 grid gap-20">
            {{-- Form --}}
            <form id="stepForm">
                {{-- Who --}}
                <div id="step1" class="grid gap-8 py-10" x-data="{ who: null }">
                    <fieldset class="mb-2">
                        <legend class="mb-8 text-lg lg:text-2xl text-center text-gray-600">How are you travelling? <span class="text-red">*</span></legend>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                            <div>
                                <input type="radio" id="solo" x-model="who" name="who" value="solo" class="radio-input">
                                <label class="col" for="solo">
                                    <img src="{{ asset('assets/front/img/single.svg') }}" class="h-12 lg:h-24">
                                    Solo
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="couple" x-model="who" name="who" value="couple" class="radio-input">
                                <label class="col" for="couple">
                                    <img src="{{ asset('assets/front/img/couple.svg') }}" class="h-12 lg:h-24">
                                    Couple
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="family" x-model="who" name="who" value="family" class="radio-input">
                                <label class="col" for="family">
                                    <img src="{{ asset('assets/front/img/family.svg') }}" class="h-12 lg:h-24">
                                    Family
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="group" x-model="who" name="who" value="group" class="radio-input">
                                <label class="col" for="group">
                                    <img src="{{ asset('assets/front/img/group.svg') }}" class="h-12 lg:h-24">
                                    Group
                                </label>
                            </div>
                        </div>
                        <div id="who-error"></div>
                    </fieldset>

                    <div class="flex flex-wrap gap-8" x-cloak x-show="who==='family' || who ==='group'">
                        <div class="form-group">
                            <label for="adults">
                                No. of adults <span class="text-red">*</span>
                            </label>
                            <select id="adults" name="no_of_adults" class="form-control">
                                <option selected>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="children">
                                No. of children <span class="text-red">*</span>
                            </label>
                            <select id="children" name="no_of_children" class="form-control">
                                <option selected>0</option>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                                <option>6</option>
                                <option>7</option>
                                <option>8</option>
                                <option>9</option>
                                <option>10</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- When --}}
                <div id="step2" class="grid gap-8 py-10" x-data="{ when: null }">
                    <fieldset>
                        <legend class="mb-8 text-lg lg:text-3xl text-center">Arrival date <span class="text-red">*</span>
                        </legend>
                        <div class="grid lg:grid-cols-3 gap-8">
                            <div>
                                <input type="radio" id="exact-date" x-model="when" name="when" value="exact" class="radio-input">
                                <label for="exact-date">
                                    <img src="{{ asset('assets/front/img/exact-date.svg') }}" class="h-12">
                                    I have exact travel dates.
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="approx-date" x-model="when" name="when" value="approx" class="radio-input">
                                <label for="approx-date">
                                    <img src="{{ asset('assets/front/img/approx-date.svg') }}" class="h-12">
                                    I have approximate travel dates.
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="decide-later" x-model="when" name="when" value="later" class="radio-input">
                                <label for="decide-later">
                                    <img src="{{ asset('assets/front/img/decide-later.svg') }}" class="h-12">
                                    I will decide later.
                                </label>
                            </div>
                        </div>
                        <div id="when-error"></div>
                    </fieldset>

                    <div class="flex flex-wrap gap-8">
                        <div class="form-group" x-cloak x-show="when==='exact'">
                            <label for="arrival-date">
                                Arrival date <span class="text-red">*</span>
                            </label>
                            <input type="date" name="arrival_date" id="arrival-date">
                        </div>
                        <div class="form-group" x-cloak x-show="when ==='exact'">
                            <label for="departure-date">
                                Departure date <span class="text-red">*</span>
                            </label>
                            <input type="date" name="departure_date" id="departure-date">
                        </div>
                        <div class="form-group" x-cloak x-show="when ==='approx'">
                            <label for="approx-month">
                                Select month <span class="text-red">*</span>
                            </label>
                            <input type="month" name="month" id="approx-month">
                        </div>
                    </div>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- Where --}}
                <div id="step3" class="grid gap-8 py-10">
                    <fieldset>
                        <legend class="mb-8 text-lg lg:text-3xl text-center">Choose your destination <span class="text-red">*</span></legend>
                        <div class="grid lg:grid-cols-4 gap-8 mb-8">
                            @forelse ($destinations as $destination)
                                <div>
                                    <input type="checkbox" id="{{ $destination->name }}" name="destination[]" value="{{ $destination->id }}" class="check-input destination-checkbox">
                                    <label for="{{ $destination->name }}">
                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                            <rect x="0" y="0" width="20" height="20" fill="white" stroke="currentColor" />
                                            <path class="check" clip-rule="evenodd" fill-rule="evenodd"
                                                d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z">
                                            </path>
                                        </svg>
                                        {{ $destination->name }}
                                    </label>
                                </div>
                            @empty
                            @endforelse
                        </div>

                        <div>
                            <input type="checkbox" id="not-sure" name="destination[]" value="not-sure" class="check-input">
                            <label for="not-sure">
                                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                    <rect x="0" y="0" width="20" height="20" fill="white" stroke="currentColor" />
                                    <path class="check" clip-rule="evenodd" fill-rule="evenodd"
                                        d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z">
                                    </path>
                                </svg>
                                I am not sure!
                            </label>
                        </div>
                        <div id="destination-error"></div>
                    </fieldset>

                    <fieldset>
                        <div class="flex flex-wrap justify-between mb-4">
                            <legend class="text-lg text-center">Choose the trip(s) you are interested in <span class="text-red">*</span></legend>
                        </div>
                        <div id="trips-block" class="grid lg:grid-cols-3 gap-8 ">
                        </div>
                        <div id="trip-interested-error"></div>
                        <div class="flex items-center" style="justify-content: center; margin-top: 50px;">
                            <div id="spinner-block"></div>
                            <button id="show-more" class="btn btn-accent btn-sm" style="display: none; margin-bottom: 50px;">show
                                more</button>
                        </div>
                    </fieldset>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- Accomodation --}}
                <div id="step4" class="grid gap-8 py-10">
                    <fieldset>
                        <legend class="mb-8 text-lg lg:text-3xl text-center">Preferred accomodation standard <span class="text-red">*</span></legend>
                        <div class="grid lg:grid-cols-4 gap-8">
                            <div>
                                <input type="radio" id="basic" name="accomodation" value="solo" class="radio-input">
                                <label class="col" for="basic">
                                    <img src="{{ asset('assets/front/img/basic.svg') }}" class="h-12 lg:h-24">
                                    Basic
                                </label>
                            </div>

                            {{-- <div>
                                <input type="radio" id="comfortable" name="accomodation" value="couple" class="radio-input">
                                <label class="col" for="comfortable">
                                    <img src="{{ asset('assets/front/img/comfortable.svg') }}" class="h-12 lg:h-24">
                                    Comfortable
                                </label>
                            </div> --}}

                            <div>
                                <input type="radio" id="luxury" name="accomodation" value="family" class="radio-input">
                                <label class="col" for="luxury"><img src="{{ asset('assets/front/img/luxury.svg') }}" class="h-12 lg:h-24">
                                    Luxury
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="camping" name="accomodation" value="family" class="radio-input">
                                <label class="col" for="camping">
                                    <img src="{{ asset('assets/front/img/camping.svg') }}" class="h-12 lg:h-24">
                                    Camping
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="self-booking" name="accomodation" value="family" class="radio-input">
                                <label class="col" for="self-booking">
                                    <img src="{{ asset('assets/front/img/self-booking.svg') }}" class="h-12 lg:h-24">
                                    Self booking
                                </label>
                            </div>
                        </div>
                        <div id="accomodation-error"></div>
                    </fieldset>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- Budget --}}
                <div id="step5" class="grid gap-8 py-10">
                    <fieldset>
                        <div class="bg-light p-4">
                            <legend class="mb-4 text-lg lg:text-2xl text-center">Budget range (per person) <span class="text-red">*</span></legend>
                            Budget range
                            <div class="custom-slider-container">
                                <div id="slider-range"></div>
                                <input class="price-range-input mt-10 bg-transparent" type="text" id="amount" name="amount" readonly style="border:0; color:black; font-size:16px;"
                                    value="$0 - $10000">
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <legend class="mb-4 text-lg lg:text-2xl text-center">Are you flexible with a change in budget?
                            <span class="text-red">*</span>
                        </legend>
                        <div class="grid lg:grid-cols-2 gap-8">
                            <div>
                                <input type="radio" id="flexible" name="flexible" value="solo" class="radio-input">
                                <label for="flexible">
                                    <img src="{{ asset('assets/front/img/flexible-budget.svg') }}" class="h-12">
                                    Yes, I am flexible. Plan the best trip for me.
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="not-flexible" name="flexible" value="couple" class="radio-input">
                                <label for="not-flexible">
                                    <img src="{{ asset('assets/front/img/fixed-budget.svg') }}" class="h-12">
                                    No, that is my maximum and minimum budget.
                                </label>
                            </div>
                        </div>
                        <div id="flexible-error"></div>
                    </fieldset>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- Tailor-made tour --}}
                <div id="step6" class="grid gap-8 py-10">
                    <fieldset>
                        <legend class="mb-2">Trip type you are looking for<span class="text-red">*</span>
                        </legend>
                        <div class="flex gap-1">
                            <div>
                                <input type="radio" id="tailor-made" name="trip_type" value="tailor-made" class="radio-input-compact">
                                <label for="tailor-made">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                        <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                        <circle cx="10" cy="10" r="6" class="check" />
                                    </svg>
                                    Tailor-made
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="type-group" name="trip_type" value="group" class="radio-input-compact">
                                <label for="type-group">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                        <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                        <circle cx="10" cy="10" r="6" class="check" />
                                    </svg>
                                    Group
                                </label>
                            </div>
                        </div>
                        <div id="trip-type-error"></div>
                    </fieldset>

                    <fieldset>
                        <legend class="mb-2">Current phase of trip planning<span class="text-red">*</span>
                        </legend>
                        <div class="flex flex-wrap gap-1">
                            <div>
                                <input type="radio" id="planning" name="plan_phase" value="planning" class="radio-input-compact">
                                <label for="planning">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                        <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                        <circle cx="10" cy="10" r="6" class="check" />
                                    </svg>
                                    I am still planning on my trip.
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="ready" name="plan_phase" value="ready" class="radio-input-compact">
                                <label for="ready">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                        <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                        <circle cx="10" cy="10" r="6" class="check" />
                                    </svg>
                                    I am ready to start.
                                </label>
                            </div>

                            <div>
                                <input type="radio" id="book" name="plan_phase" value="book" class="radio-input-compact">
                                <label for="book">
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                        <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                        <circle cx="10" cy="10" r="6" class="check" />
                                    </svg>
                                    I want to book.
                                </label>
                            </div>
                        </div>
                        <div id="plan-phase-error"></div>
                    </fieldset>

                    <div class="grid lg:grid-cols-2 gap-8">
                        <div>
                            <label for="additional-queries" class="mb-2">
                                Any additional queries?
                            </label>
                            <div class="form-group">
                                <textarea id="additional-queries" name="additional_queries" class="form-control"></textarea>
                            </div>
                        </div>
                        <div>
                            <label for="departure-date" class="mb-2">
                                How did you hear about us? <span class="text-red">*</span>
                            </label>
                            <div class="form-group">
                                <select id="departure-date" name="reached_by" class="form-control">
                                    <option value="">Select One</option>
                                    <option value="Blog">Blog</option>
                                    <option value="Club/Association">Club/Association</option>
                                    <option value="Facebook">Facebook</option>
                                    <option value="Friend Recommendation">Friend Recommendation</option>
                                    <option value="Instagram">Instagram</option>
                                    <option value="Internet Search">Internet Search</option>
                                    <option value="Lonely Planet Guides">Lonely Planet Guides</option>
                                    <option value="New York Times">New York Times</option>
                                    <option value="Newspaper Article">Newspaper Article</option>
                                    <option value="Online Advertising">Online Advertising</option>
                                    <option value="Past Client">Past Client</option>
                                    <option value="Trade Partners">Trade Partners</option>
                                    <option value="Trade Show">Trade Show</option>
                                    <option value="Trek Leader/Staff Recommended">Trek Leader/Staff Recommended</option>
                                    <option value="Trip Advisor">Trip Advisor</option>
                                    <option value="Twitter">Twitter</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-center gap-8 p-10">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button type="button" class="btn btn-accent next">Next</button>
                    </div>
                </div>

                {{-- Personal Information --}}
                <div id="step7" class="grid gap-8 py-10">
                    <h2 class="text-lg lg:text-2xl">PERSONAL INFORMATION</h2>
                    <p>Please fill in the form below. Our customer support will get back to you as soon as possible.</p>
                    <div class="grid lg:grid-cols-2 gap-8">
                        <div>
                            <label for="first-name">First Name <span class="text-red">*</span></label>
                            <div class="form-group">
                                <input type="text" id="first-name" name="first_name" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label for="last-name">Last Name <span class="text-red">*</span></label>
                            <div class="form-group">
                                <input type="text" id="last-name" name="last_name" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label for="contact-no">Contact number <span class="text-red">*</span></label>
                            <div class="form-group">
                                <input type="tel" id="contact-no" name="contact_number" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label for="email">Email <span class="text-red">*</span></label>
                            <div class="form-group">
                                <input type="email" id="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div>
                            <label for="nationality">Nationality <span class="text-red">*</span></label>
                            <div class="form-group">
                                @include('front.elements.country')
                            </div>
                        </div>
                        <fieldset>
                            <legend>Preferred method of contact<span class="text-red">*</span></legend>
                            <div class="flex flex-wrap gap-1">
                                <div>
                                    <input type="radio" id="method-email" name="contact_method" value="email" class="radio-input-compact">
                                    <label for="method-email">
                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                            <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                            <circle cx="10" cy="10" r="6" class="check" />
                                        </svg>
                                        Email
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="method-phone" name="contact_method" value="phone" class="radio-input-compact">
                                    <label for="method-phone">
                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                            <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                            <circle cx="10" cy="10" r="6" class="check" />
                                        </svg>
                                        Phone
                                    </label>
                                </div>

                                <div>
                                    <input type="radio" id="method-both" name="contact_method" value="both" class="radio-input-compact">
                                    <label for="method-both">
                                        <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" class="w-6 h-6">
                                            <circle cx="10" cy="10" r="9" fill="white" stroke="currentColor" />
                                            <circle cx="10" cy="10" r="6" class="check" />
                                        </svg>
                                        Both
                                    </label>
                                </div>
                            </div>
                            <div id="contact-method-error"></div>
                        </fieldset>
                    </div>

                    <div>
                        <input type="checkbox" id="privacy-policy" name="privacy_policy">
                        <label for="privacy-policy">
                            I have read and accept the <a href="{{ url('/privacy-policy') }}" class="text-accent">Privacy Policy</a>. <span class="text-red">*</span>
                        </label>
                        <div id="privacy-policy-error"></div>
                    </div>

                    <div class="flex justify-center gap-8">
                        <button type="button" class="btn btn-muted back">Back</button>
                        <button id="finish-btn" type="button" class="btn btn-accent finish">Finish</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="{{ asset('assets/vendors/jquery-validation/dist/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-validation/dist/additional-methods.min.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function() {
            var session_success_message = '{{ $session_success_message ?? '' }}';
            var session_error_message = '{{ $session_error_message ?? '' }}';
            if (session_success_message) {
                toastr.success(session_success_message);
            }

            if (session_error_message) {
                toastr.danger(session_error_message);
            }

            $(document).on('click', '#make_a_payment_btn', function(ev) {
                ev.preventDefault();
                let btn = $(this);
                btn.prop('disabled', true);
                btn.html('submitting...');
                setTimeout(() => {
                    $("#captcha-form").submit();
                }, 1000);
            });
        });
    </script>
    <script>
        $(function() {
            let currentPage = 1;
            let totalPage;
            let nextPage;
            var currentStep = 1;
            var form = $("#stepForm");
            var validator = form.validate();
            var formSteps = $("#stepForm>div");

            $("#slider-range").slider({
                classes: {
                    "ui-slider": "custom-slider"
                },
                range: true,
                min: 0,
                max: 10000,
                values: [0, 10000],
                change: function(event, ui) {
                    performSearch();
                },
                slide: function(event, ui) {
                    $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                }
            });

            $(".destination-checkbox").on('change', async function(event) {
                currentPage = 1;
                $("#trips-block").html("");
                const trips = await getTripsByDestinationID();
                addTripsToDiv(trips);
            });

            function addTripsToDiv(trips) {
                let html = "";
                const selected_trip_id = "{!! $selected_trip_id !!}";
                for (const trip of trips) {
                    html += `<div class="destination-trip">\
                                <input type="checkbox" id="trip${trip.id}" name="trip_interested[]" value="${trip.id}"\
                                    class="check-input">\
                                <label for="trip${trip.id}">\
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"\
                                        aria-hidden="true" class="w-6 h-6">\
                                        <rect x="0" y="0" width="20" height="20"\
                                            fill="white" stroke="currentColor" />\
                                        <path class="check" clip-rule="evenodd" fill-rule="evenodd"\
                                            d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z">\
                                        </path>\
                                    </svg>\
                                    ${trip.name}\
                                </label>\
                            </div>`;
                }
                $("#trips-block").append(html);
            }

            initDestination();

            function initDestination() {
                const selected_destinations = "{!! $all_selected_destinations !!}";
                if (selected_destinations.length > 0) {
                    const boxes = $(".destination-checkbox");
                    boxes.each(function(i, v) {
                        const dest_id = $(v).val();
                        if (selected_destinations.includes(dest_id)) {
                            $(v).prop('checked', true);
                        }
                    });
                    // get the selected trips and make it selected
                    const selected_trip_id = "{!! $selected_trip_id !!}";
                    const trip_name = "{!! $selected_trip_name !!}";
                    const html = `<div class="destination-trip">\
                                <input type="checkbox" id="trip${selected_trip_id}" checked name="trip_interested[]" value="${selected_trip_id}"\
                                    class="check-input">\
                                <label for="trip${selected_trip_id}">\
                                    <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"\
                                        aria-hidden="true" class="w-6 h-6">\
                                        <rect x="0" y="0" width="20" height="20"\
                                            fill="white" stroke="currentColor" />\
                                        <path class="check" clip-rule="evenodd" fill-rule="evenodd"\
                                            d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z">\
                                        </path>\
                                    </svg>\
                                    ${trip_name}\
                                </label>\
                            </div>`;
                    $("#trips-block").append(html);
                } else {
                    $(".destination-checkbox:first").click();
                }
            }

            $("#show-more").on('click', async function(event) {
                event.preventDefault();
                if (nextPage) {
                    currentPage++;
                    const trips = await getTripsByDestinationID(currentPage);
                    addTripsToDiv(trips);
                    if (!nextPage) {
                        $("#show-more").hide();
                    }
                }
            });

            function getTripsByDestinationID() {
                return new Promise((resolve, reject) => {
                    // get all the selected destination
                    const selectedDestinationArr = [];
                    $('.destination-checkbox:checked').each(function() {
                        selectedDestinationArr.push($(this).val());
                    });
                    let url = '{!! route('front.destinations.gettrips') !!}' + `?ids=${selectedDestinationArr.join(',')}&page=${currentPage}`;
                    let result = [];
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        async: "false",
                        beforeSend: function(xhr) {
                            var spinner =
                                '<button style="margin:0 auto;" class="btn btn-sm btn-primary text-white" type="button" disabled>\
                                                                                                                                                                                                                                                                                                                                                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>\
                                                                                                                                                                                                                                                                                                                                                                            Loading Trips...\
                                                                                                                                                                                                                                                                                                                                                                            </button>';
                            $("#spinner-block").html(spinner);
                            $("#show-more").hide();
                        },
                        success: function(res) {
                            if (res.success) {
                                result = res.data.data;
                                totalPage = res.data.total;
                                currentPage = res.data.current_page;
                                nextPage = (res.data.next_page_url) ? true : false;
                            }
                        }
                    }).done(function(data) {
                        $("#spinner-block").html('');
                        if (!nextPage) {
                            $("#show-more").hide();
                        } else {
                            $("#show-more").show();
                        }
                        resolve(result);
                    });
                });
            }

            const stepBlock = {
                step1: "step-who",
                step2: "step-when",
                step3: "step-where",
                step4: "step-accomodation",
                step5: "step-budget",
                step6: "step-tailor-made",
                step7: "step-tailor-made"
            }

            var validationRules = {
                step1: {
                    who: {
                        required: true
                    },
                    no_of_adults: {
                        required: true
                    },
                    no_of_children: {
                        required: true
                    }
                },
                step2: {
                    when: {
                        required: true
                    },
                    arrival_date: {
                        required: true
                    },
                    departure_date: {
                        required: true
                    },
                    month: {
                        required: true
                    }
                },
                step3: {
                    "destination[]": {
                        required: true
                    },
                    "trip_interested[]": {
                        required: true
                    }
                },
                step4: {
                    accomodation: {
                        required: true
                    }
                },
                step5: {
                    flexible: {
                        required: true
                    }
                },
                step6: {
                    trip_type: {
                        required: true
                    },
                    plan_phase: {
                        required: true
                    },
                    reached_by: {
                        required: true
                    }
                },
                step7: {
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    contact_number: {
                        required: true
                    },
                    email: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    contact_method: {
                        required: true
                    },
                    privacy_policy: {
                        required: true
                    },
                }
            };

            function selectStep(name) {
                const step = $("#step-block>button");
                const index = $(`#${name}`).index();
                step.each(function(i, v) {
                    const el = $(v);
                    if (i <= index) {
                        el.addClass('active');
                    } else {
                        el.removeClass('active');
                    }
                });
            }

            function nextStep() {
                var currentFieldset = formSteps.eq(currentStep - 1);
                const validationGroup = validationRules["step" + currentStep];
                validator.destroy();
                form.validate({
                    rules: validationGroup,
                    errorPlacement: function(error, element) {
                        if (element.attr("name") == "who") {
                            $("#who-error").html(error);
                            // error.insertAfter("#lastname");
                        } else if (element.attr("name") == "when") {
                            $("#when-error").html(error);
                        } else if (element.attr("name") == "destination[]") {
                            $("#destination-error").html(error);
                        } else if (element.attr("name") == "trip_interested[]") {
                            $("#trip-interested-error").html(error);
                        } else if (element.attr("name") == "accomodation") {
                            $("#accomodation-error").html(error);
                        } else if (element.attr("name") == "flexible") {
                            $("#flexible-error").html(error);
                        } else if (element.attr("name") == "trip_type") {
                            $("#trip-type-error").html(error);
                        } else if (element.attr("name") == "plan_phase") {
                            $("#plan-phase-error").html(error);
                        } else if (element.attr("name") == "contact_method") {
                            $("#contact-method-error").html(error);
                        } else if (element.attr("name") == "privacy_policy") {
                            $("#privacy-policy-error").html(error);
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    submitHandler: function(form) {
                        // console.log();
                        var formData = new FormData($(form)[0]);
                        formData.append('amount', $("#slider-range").slider("values"));
                        // var formData = $(form).serialize();
                        $.ajax({
                            url: "{{ route('front.plantrip.create') }}",
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            processData: false,
                            contentType: false,
                            async: false,
                            success: function(res) {
                                if (res.status === 1) {
                                    location.href = "{{ route('front.plantrip.thank-you') }}";
                                    // form[0].reset();
                                    // $('#cropper-image').attr('src', '{{ asset('img/default.gif') }}');
                                    // if (cropped) {
                                    //   cropper.destroy();
                                    // }
                                    // $('#summernote-description').summernote('reset');
                                }
                            }
                        });
                    }
                });
                // var validationMessagesGroup = validationMessages["step" + currentStep];

                if (form.valid()) {
                    if (currentStep === 7) {
                        form.submit();
                        return;
                    }
                    currentFieldset.hide();
                    // formSteps.eq(currentStep).show();
                    formSteps.eq(currentStep).css('display', 'grid');
                    currentStep++;
                } else {
                    // Display error messages for the current step
                    form.validate().focusInvalid();
                }

                const currentStepName = `step${currentStep}`;
                selectStep(stepBlock[currentStepName]);
            }

            function prevStep() {

                if (currentStep > 1) {
                    formSteps.eq(currentStep - 1).hide();
                    formSteps.eq(currentStep - 2).show();
                    currentStep--;
                }
                const currentStepName = `step${currentStep}`;
                selectStep(stepBlock[currentStepName]);
            }

            formSteps.each(function(index) {
                $(this).find("button.next").on("click", function(e) {
                    e.preventDefault();
                    nextStep();
                    window.scrollTo(0, 238);
                });

                if (index > 0) {
                    $(this).find("button.back").on("click", function(e) {
                        e.preventDefault();
                        prevStep();
                        window.scrollTo(0, 238);
                    });
                }
            });

            $("#finish-btn").on('click', function(event) {
                event.preventDefault();
                nextStep();
            });

            // Apply validation rules and messages for each step
            // formSteps.each(function(index) {
            //     var fieldsetID = $(this).attr("id");
            //     var validationGroup = validationRules["step1"];
            //     // var validationMessagesGroup = validationMessages[fieldsetID];

            //     form.validate({
            //         rules: validationGroup,
            //         // messages: validationMessagesGroup
            //     });
            // });
        });
    </script>
@endpush
