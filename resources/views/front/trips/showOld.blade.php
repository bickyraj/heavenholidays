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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
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
@extends('layouts.front_inner')
@section('meta_og_title'){!! $trip->trip_seo->meta_title??'' !!}@stop
@section('meta_description'){!! $trip->trip_seo->meta_description??'' !!}@stop
@section('meta_keywords'){!! $trip->trip_seo->meta_keywords??'' !!}@stop
@section('meta_og_url'){!! $trip->trip_seo->canonical_url??'' !!}@stop
@section('meta_og_description'){!! $trip->trip_seo->meta_description??'' !!}@stop
@section('meta_og_image'){!! $trip->trip_seo->ogImageUrl??'' !!}@stop
@push('styles')
<script src="https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit" async defer></script>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.8.0/dist/alpine.min.js" defer></script>
@endpush
@section('content')
<!-- Hero -->
<!-- Slider -->
<section class="hero relative">

    <div id="hero-slider" class="hero-slider">
        @if(iterator_count($trip->trip_galleries))
        @foreach($trip->trip_galleries as $gallery)
           <div class="slide">
               <img src="{{ $gallery->imageUrl }}" alt="">
           </div>
        @endforeach
        @endif
    </div><!-- Slider -->
    <div class="overlay lg:absolute">
        <div class="container flex jcsb wrap">
            <div class="caption">
                <h2>{{ $trip->name }}</h2>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.trips.listing') }}">Trips</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $trip->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="ratings-wrapper">
                <div class="ratings d-flex align-items-center bg-primary px-3 py-1 text-secondary">
                    <div>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <div class="fs-xs">from 25 reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--<section class="hero relative">
    <img src="{{ $trip->imageUrl }}" alt="">
    <div class="overlay absolute">
        <div class="container flex jcsb wrap">
            <div class="caption">
                <h2>{{ $trip->name }}</h2>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('front.trips.listing') }}">Trips</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $trip->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="ratings-wrapper">
                <div class="ratings d-flex align-items-center bg-primary px-3 py-1 text-secondary">
                    <div>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <svg>
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                        <div class="fs-xs">from 25 reviews</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>--}}

<!-- <div class="breadcrumb-wrapper">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fs-sm">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Nepal</a></li>
                <li class="breadcrumb-item"><a href="#">Trekkings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Everest Three Passes Trek</li>
            </ol>
        </nav>
    </div>
</div> -->

<section>

    <div class="tour-details-bar sticky-top">
        <div class="container flex jcc aic">
            <nav class="tour-details-tabs flex jcc aic" id="secondnav">
                <ul class="nav">
                    <li class=" nav-item">
                        <a href="#overview" class="nav-link"><i class="fas fa-receipt"></i> <span>Overview</span></a>
                    </li>
                    @if (!$trip->trip_itineraries->isEmpty())
                    <li class="nav-item">
                        <a href="#itinerary" class="nav-link"><i class="fas fa-clock"></i> <span>Itinerary</span></a>
                    </li>
                    @endif
                    @if ($trip->trip_include_exclude)
                    <li class="nav-item">
                        <a href="#inclusions" class="nav-link"><i class="fas fa-box-open"></i> <span>Inclusions</span></a>
                    </li>
                    @endif
                    @if (!$trip->trip_departures->isEmpty())
                    <li class="nav-item">
                        <a href="#date-price" class="nav-link"><i class="far fa-calendar-alt"></i> <span>Date & Price</span></a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="#reviews" class="nav-link"><i class="far fa-comments"></i> <span>Review</span></a>
                    </li>
                    @if (!$trip->trip_faqs->isEmpty())
                    <li class="nav-item">
                        <a href="#faqs" class="nav-link"><i class="far fa-question-circle"></i> <span>FAQs</span></a>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>

    <div class="container mt-2 mb-4">
        <div class="grid lg:grid-cols-3 xl:grid-cols-4 gap-2 xl:gap-3">
            <div class="col-2 xl:col-3 relative" id="ss">


                <div id="overview" class="tour-details-section pt-4">
                    <div class="bg-white">

                        <div class="lg:none">
                            @include('front.elements.price_card')
                        </div>
                        <!-- <h2>Overview</h2> -->
                        <div class="tabular grid lg:grid-cols-2 gap-2">
                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#calendarduration') }}" /></svg>
                                </div>
                                <div class="data">
                                    <p class="field-name">
                                        Duration
                                    </p>
                                    <p class="field-value">
                                        {{ $trip->duration }} days
                                    </p>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#maxelevation') }}" /></svg>
                                </div>
                                <div class="data">
                                    <p class="field-name">
                                        Max. Elevation
                                    </p>
                                    <p class="field-value">
                                        {{ $trip->max_altitude }}m
                                    </p>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#groupsize') }}" /></svg>
                                </div>
                                <div class="data">
                                    <p class="field-name">
                                        Group size
                                    </p>
                                    <p class="field-value">
                                       {{ $trip->group_size }}
                                    </p>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#level') }}" /></svg>
                                </div>
                                <div class="data">

                                    <div class="field-name">

                                        Level
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->difficulty_grade_value }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#transportation') }}" /></svg>
                                </div>
                                <div class="data">
                                    <div class="field-name">
                                        Transportation
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->trip_info->transportation??'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#bestseason') }}" /></svg>
                                </div>
                                <div class="data">

                                    <div class="field-name">
                                        Best Season
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->trip_info->best_season??'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#accomodation') }}" /></svg>
                                </div>
                                <div class="data">
                                    <div class="field-name">
                                        Accomodation
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->trip_info->accomodation??'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#meals') }}" /></svg>
                                </div>
                                <div class="data">
                                    <div class="field-name">
                                        Meals
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->trip_info->meals??'' }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#startsat') }}" /></svg>
                                </div>
                                <div class="data">

                                    <div class="field-name">

                                        Starts at
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->starting_point }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#endsat') }}" /></svg>
                                </div>
                                <div class="data">

                                    <div class="field-name">

                                        Ends at
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->ending_point }}
                                    </div>
                                </div>
                            </div>

                            <div class="table-item flex aic lg:col-2">
                                <div class="icon mr-2">
                                    <svg>
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#triproute') }}" /></svg>
                                </div>
                                <div class="data">

                                    <div class="field-name">

                                        Trip Route
                                    </div>
                                    <div class="field-value">
                                        {{ $trip->trip_info->trip_route??'' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="px-3 pb-2">

                            <h3>Highlights</h3>
                            <div class="highlights">
                            {!! ($trip->trip_info)?$trip->trip_info->highlights:'' !!}
                            </div>
                            {{-- <ul class="highlights mb-2">
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam, ex.</li>
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, laboriosam!</li>
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugiat, voluptatem?</li>
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor, sit amet consectetur adipisicing elit. Quasi, nemo.</li>
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus modi molestias placeat dolores,
                                    numquam atque.</li>
                                <li class="flex"><svg class="icon shrink-0 mr-1">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#star') }}" /></svg>
                                    Lorem ipsum dolor sit amet consectetur, adipisicing elit. Magnam id cumque repudiandae odio, vitae
                                    quidem mollitia doloremque eaque laudantium velit!</li>
                            </ul> --}}

                            <div id="overview-text" class="lim collapse">
                                {!! $trip->trip_info->overview??'' !!}

                            </div>
                            <p class="text-center">
                                <button id="toggle-overview" class="btn btn-gray" data-bs-toggle="collapse" data-bs-target="#overview-text">Show
                                    More</button>
                            </p>

                            <div class="trip-note bg-light mb-3">
                                <p class="font-weight-bold mb-0"><i class="fas fa-info"></i> Important Note</p>
                                <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam aspernatur corporis, quibusdam
                                    nostrum itaque cum quod quaerat ea! Unde magnam provident quod! Fugit in enim deleniti ex, tenetur modi
                                    neque?</p>
                            </div>
                        </div>
                    </div>


                </div>

                @php
                    $x_data_false = [];
                    $x_data_true = [];
                    foreach ($trip->trip_itineraries as $itinerary) {
                        $x_data_false['day' . $itinerary->id . 'Open'] = false;
                        $x_data_true['day' . $itinerary->id . 'Open'] = true;
                    }
                    $x_data_false['expandAll'] = false;
                @endphp
                <div id="itinerary" class="tour-details-section" x-data=@json($x_data_false)>
                    <div class="bg-white p-3">
                        <h2 class="fs-xl">Trip Itinerary</h2>
                        <button class="btn btn-sm btn-gray expand-all" @click="expandAll =! expandAll">Expand All</button>
                        <button class="btn btn-sm btn-gray collapse-all" @click="expandAll = false">Collapse All</button>

                        {{-- <button class="btn btn-sm btn-gray expand-all" data-bs-toggle="collapse" data-bs-target=".day-details">Expand All</button>
                        <button class="btn btn-sm btn-gray collapse-all">Collapse All</button> --}}
                        <div class="itinerary">
                            @forelse ($trip->trip_itineraries as $itinerary)
                                <div class="itinerary-row">
                                    <div class="day">
                                        <p class="d">Day</p>
                                        {{ $itinerary->day }}
                                    </div>

                                    <div class="itinerary-text">
                                        <div class="collapse-toggle">
                                            <button :aria-expanded="day{{ $itinerary->id }}Open" aria-controls="day{{ $itinerary->id }}" @click="day{{ $itinerary->id }}Open=!day{{ $itinerary->id }}Open">
                                                <h3 class="text-left fs-lg">{{ $itinerary->name }}</h3>
                                                <svg class="icon-md shrink-0">
                                                    <use xlink:href="{{ asset('assets/front/img/sprite.svg#plus') }}" />
                                                </svg>
                                            </button>
                                        </div>
                                        <div id="day{{ $itinerary->id }}" class="day-details" x-show.transition="day{{$itinerary->id}}Open || expandAll">
                                            <p>
                                                {!! $itinerary->description !!}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>
                    </div>
                </div>

                @if ($trip->trip_include_exclude)
                <div id="inclusions" class="tour-details-section">
                    <div class="bg-white p-3">
                        <h2 class="fs-xl">Inclusions</h2>
                        <div class="grid lg:grid-cols-2 gap-1">

                            @if($trip->trip_include_exclude)
                            <div class="includes">

                                <h3>Includes</h3>
                                  <?= $trip->trip_include_exclude->include; ?>
                            </div>
                            @endif
                            {{-- <ul class="includes">
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#check') }}" />
                                    </svg>

                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Tenetur dolor atque error iste!
                                    Praesentium, possimus.</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#check') }}" />
                                    </svg>

                                    Commodi dolore iure laborum illo quas accusantium, eum eos mollitia enim quos ad nisi. Mollitia.
                                </li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#check') }}" />
                                    </svg>

                                    Ad minima odit voluptatem voluptas quisquam soluta ab culpa, itaque hic vero eveniet eaque rerum?
                                </li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#check') }}" />
                                    </svg>

                                    Exercitationem error fugit non asperiores repellendus, nemo eveniet ipsum sit veritatis, eum
                                    molestias praesentium placeat!</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#check') }}" />
                                    </svg>

                                    Commodi nostrum nulla fuga, natus perspiciatis laborum hic omnis accusamus blanditiis, debitis
                                    cupiditate fugiat? Blanditiis.</li>
                            </ul> --}}

                            {{-- <ul class="excludes">
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#x') }}" /></svg>

                                    Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolorum provident accusantium quos
                                    eligendi, aliquid earum.</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#x') }}" /></svg>

                                    Fuga sed impedit eius, ipsum ratione, at veritatis quam quae magni, id tenetur suscipit quos?</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#x') }}" /></svg>

                                    Aliquid molestias dolorem iusto aut recusandae cum repellendus mollitia deserunt, reprehenderit at
                                    quia accusamus enim.</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#x') }}" /></svg>

                                    Obcaecati quod accusamus accusantium aliquam suscipit, ab quas delectus, officiis possimus
                                    consequatur quasi fugiat aliquid?</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#x') }}" /></svg>

                                    Sint cum perferendis autem, temporibus esse laudantium maxime sit assumenda aperiam, voluptas amet
                                    neque accusamus?</li>
                            </ul> --}}
                            @if($trip->trip_include_exclude)
                            <div class="excludes">
                                <h3>Excludes</h3>
                                    <?= $trip->trip_include_exclude->exclude; ?>
                            </div>
                            @endif
                        </div>
                        <div class="complimentary p-2">
                            <h3>Complimentary</h3>

                            <div class="trip-includes" style="padding: 0px;">
                              <?= $trip->trip_include_exclude->complimentary ??''; ?>
                            </div>
                            {{-- <ul>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#plus') }}" /></svg>

                                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus expedita aut iure quis odit
                                    dignissimos.</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#plus') }}" /></svg>

                                    Perferendis quasi reiciendis repellendus ratione, iure atque quia voluptatibus fugiat quaerat
                                    voluptatem, aperiam a dolorem?</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#plus') }}" /></svg>

                                    Voluptate totam maiores neque laudantium. Placeat maxime odit sequi tenetur quo vero? Commodi, aperiam
                                    deleniti?</li>
                                <li class="flex">
                                    <svg class="icon-md mr-1 shrink-0">
                                        <use xlink:href="{{ asset('assets/front/img/sprite.svg#plus') }}" /></svg>

                                    Quibusdam commodi laboriosam quia voluptatum velit officiis, explicabo nulla! Impedit, ut? Totam porro
                                    tenetur magnam.</li>
                            </ul> --}}
                        </div>
                    </div>
                </div>
                @endif

                @if (!$trip->trip_departures->isEmpty())
                <div id="date-price" class="tour-details-section">
                    <div class="bg-white p-3">
                        <!-- <h2>Date & Price</h2> -->
                        <h2 class="fs-xl">Upcoming Departure Dates</h2>
                        <!-- <h3>Upcoming Departure Dates</h3> -->
                        <div class="table-responsive">
                          <table class="table mb-2">
                            <thead>
                              <th>Start Date</th>
                              <th>End Date</th>
                              <th>Offer Price</th>
                              <th>Status</th>
                              <th></th>
                            </thead>
                            <tbody>
                              @foreach($trip->trip_departures as $departure)
                              <tr>
                                <td>{{ formatDate($departure->from_date) }}</td>
                                <td>{{ formatDate($departure->to_date) }}</td>
                                <td>
                                  <small class="text-gray"><s>USD {{ number_format($trip->cost) }}</s></small><br>
                                  <span class="text-green">USD <b>{{ number_format($departure->price) }}</b></span>
                                </td>
                                <td><span class="text-success">{{ $departure->statusInfo }}</span></td>
                                <td><a href="{{ route('front.trips.departure-booking', ['slug' => $trip->slug, 'id' => $departure->id]) }}" class="btn btn-sm btn-theme">Book now</a></td>

                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                    </div>
                </div>
                @endif

                <div id="reviews" class="tour-details-section">
                    <div class="bg-white p-3">
                        <h2 class="fs-xl">Reviews</h2>
                        <div class="grid gap-1 mb-2">
                            @if(iterator_count($trip->trip_reviews))
                                @foreach($trip->trip_reviews()->where('status', 1)->get() as $review)
                                    <div class="review">
                                        <div class="review__content">
                                            <h2 class="fs-lg">{{ $review->title }}</h2>
                                            <p class="fs-sm">{{ $review->review }}</p>
                                        </div>
                                        <div class="review__person">
                                            <div class="image">
                                                <img src="{{ $review->thumbImageUrl }}" alt="">
                                            </div>
                                            <div>
                                                <div class="name">{{ $review->review_name }}</div>
                                                <div class="from">{{ $review->review_country }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <p class="text-center">
                            <a href="{{ route('front.reviews.create') }}" class="btn btn-theme mr-1" data-toggle="modal" data-target="#review-modal">
                                Write a review</a>
                            <a href="{{ route('front.reviews.index') }}" class="theme">See more reviews
                                <svg><use xlink:href="{{ asset('assets/front/img/sprite.svg#arrownarrowright') }}" /></svg>
                            </a>
                        </p>
                    </div>
                </div>

                {{-- faqs --}}
                @if(!$trip->trip_faqs->isEmpty())
                <div id="faqs" class="tour-details-section mb-4">
                    <div class="bg-white p-3">
                        <h2 class="fs-xl">Frequently Asked Questions</h2>
                        <div class="accordion" id="faq-accordion">
                            @foreach($trip->trip_faqs as $faq)
                                <div class="accordion-item">
                                    <h3 class="accordion-header mb-0" id="heading{{ $faq->id }}">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="true" aria-controls="collapse{{ $faq->id }}">
                                            {{ $faq->title }}
                                        </button>
                                    </h3>
                                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $faq->id }}" data-bs-parent="#faq-accordion">
                                        <div class="accordion-body fs-sm">
                                            {!! $faq->description !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
                <p class="mb-5">
                    <a href="{{ route('front.trips.booking', $trip->slug) }}" class="btn btn-theme">Book Now</a>
                </p>

                <!-- <div class="share-links mb-5">
                    <h3>Share this tour</h3>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-pinterest"></i></a>
                    <a href="#"><i class="fab fa-blogger-b"></i></a>
                    <a href="#"><i class="fab fa-google-plus-g"></i></a>
                </div> -->

            </div>
            <aside class="py-4">

                @include('front.elements.price_card')

                <div class=" mb-3 text bg-white p-2">
                    <h4 class="mb-1">
                        You can customize this trip
                    </h4>
                    <ul class="mb-2 fs-sm">
                        <li><svg class="icon">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#questionmarkcircle') }}" /></svg>
                            Have a big group?</li>
                        <li><svg class="icon">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#questionmarkcircle') }}" /></svg>
                            Budget problem?</li>
                        <li><svg class="icon">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#questionmarkcircle') }}" /></svg>
                            Date & Itinerary problem?</li>
                        <li><svg class="icon">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#questionmarkcircle') }}" /></svg>
                            Wanna add / remove services?</li>
                    </ul>
                    <div class="fs-xs mb-2">
                        All right, we'll help you personalize your trips
                    </div>
                    <a href="{{ route('front.trips.customize', $trip->slug) }}" class="btn btn-theme" title="Customize this tour">
                        Customize
                        <svg class="icon">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#adjustments') }}" /></svg>
                    </a>
                </div>

                @include('front.elements.enquiry')

                <div class="mb-4">
                    <div class="card-header">
                        <h2 class="mb-2">Map & Route</h2>
                    </div>
                    <div class="card-body p-0">
                        <a data-fancybox data-caption="Annapurna Base Camp Trek Map" href="img/annapurnaregion-01.jpg">
                            <img class="img-fluid" src="{{ asset('assets/front/img/annapurnaregion-01.jpg') }}" alt="">
                        </a>
                    </div>
                </div>

                <div class="experts-card">
                    <div class="grid grid-cols-3">
                        <div class="col-2">
                            <p class="mb-0">Still confused?</p>
                            <h3 class="mb-2">Talk to our experts</h3>
                        </div>
                        <div>
                            <svg class="icon-lg">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#customersupport') }}" /></svg>
                        </div>
                    </div>
                    <div class="experts-phone flex mb-1"><a href="#" class="flex aic"><svg class="icon-md mr-1">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#phone') }}" /></svg>
                            +977 -9851071767</a></div>
                    <div class="experts-phone flex mb-3"><a href="mailto:" class="flex aic"><svg class="icon-md mr-1">
                                <use xlink:href="{{ asset('assets/front/img/sprite.svg#mail') }}" /></svg> info@holidaytoursnepal.com</a></div>


                </div>

                <div class="mb-3 p-2 bg-light">
                    <a href="{{ Setting::get('facebook') }}" class="text-primary hover:text-accent mr-1">
                        <svg class="icon-md">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#facebookmessenger') }}" /></svg>
                    </a>
                    <a href="{{ Setting::get('viber') }}" class="text-primary hover:text-accent mr-1">
                        <svg class="icon-md">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#viber') }}" /></svg>
                    </a>
                    <a href="{{ Setting::get('whatsapp') }}" class="text-primary hover:text-accent mr-1">
                        <svg class="icon-md">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#whatsapp') }}" /></svg>
                    </a>
                    <a href="{{ Setting::get('skype') }}" class="text-primary hover:text-accent mr-1">
                        <svg class="icon-md">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#skype') }}" /></svg>
                    </a>
                    {{-- <a href="{{ Setting::get('viber') }}" class="text-primary hover:text-accent mr-1">
                        <svg class="icon-md">
                            <use xlink:href="{{ asset('assets/front/img/sprite.svg#weixin') }}" /></svg>
                    </a> --}}
                </div>

                {{-- essential trip informations --}}
                @include('front.elements.essential_trip_information')

                @if(iterator_count($trip->addon_trips))
                <div class="mb-3">
                        @foreach($trip->addon_trips as $addon_trip)
                            @include('front.elements.addon_trip', ['trip' => $addon_trip])
                        @endforeach
                    </div>
                @endif



                <div class="sticky-top sticky-price none lg:block">
                    @include('front.elements.price_card')
                </div>

                <!-- <div class="features card mb-3">
                        <div class="card-header">
                            <h3>Why Us</h3>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Full Board Included Price</li>
                                <li>Best Price Guaranteed</li>
                                <li>Guaranteed Departure Dates</li>
                                <li>Well-crafted Itinerary</li>
                            </ul>
                        </div>
                    </div>

                    <div class="latest-blog card mb-3">
                        <div class="card-body p-0">
                            <div class="latest-blog-card">
                                <b>Latest news/blog</b>
                            </div>
                            <div class="latest-blog-card">
                                <h1 class="title">Everest Base Camp Treksdfa sdfasdfa dfasd</h1>
                                <a href="" class="stretched-link"></a>
                            </div>
                            <div class="latest-blog-card">
                                <h1 class="title">Everest Base Camp Treksdfa sdfasdfa dfasd</h1>
                                <a href="" class="stretched-link"></a>
                            </div>
                            <div class="latest-blog-card">
                                <h1 class="title">Everest Base Camp Treksdfa sdfasdfa dfasd</h1>
                                <a href="" class="stretched-link"></a>
                            </div>
                        </div>
                    </div>

                    <div class="ta-widget">
                        <img src="{{ asset('assets/front/img/ta-widget.jpg') }}" alt="" class="img-fluid">
                    </div> -->
            </aside>

        </div>
    </div>

    <!-- Featured -->
    @if (!$trip->similar_trips->isEmpty())
    <div class="featured section bg-light py-4">
        <div class="container">
            <h2 class="fs-lg mb-2">Similar Tours</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-3">
                @forelse ($trip->similar_trips as $trip)
                    @include('front.elements.tour-card', ['tour' => $trip])
                @empty

                @endforelse
            </div>
        </div>
    </div> <!-- Featured -->
    @endif
</section>


<!-- Write a review modal -->
<!-- <div class="modal fade" id="review-modal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-primary mb-0" id="exampleModalLabel">Write a review</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-primary font-weight-bold">Everest Three Passes Trek</h5>
                <form action="">
                    <div class="form-group">
                        <label for="photo-input">Photo</label>
                        <input type="file" class="form-control-file" id="photo-input">
                        <img src="{{ asset('assets/front/img/person-placeholder.jpg') }}" alt="" id="write-review-photo">
                    </div>
                    <!-- <div class="form-row">
        <div class="col-md-6"> -->
<!-- <div class="form-group icon">
    <input type="text" class="form-control" placeholder="Name">
    <i class="fas fa-user"></i>
</div>
<!~~ </div>
        <div class="col-md-6"> ~~>
<div class="form-group icon">
    <!~~ <label for="">Country</label> ~~>
    <input type="text" class="form-control" placeholder="Country" list="countries">
    <i class="fas fa-flag"></i>

</div>
<!~~ </div>
        <div class="col-md-6"> ~~>
<div class="form-group icon">
    <textarea type="text" rows="5" class="form-control" placeholder="Review"></textarea>
    <i class="fas fa-comment"></i>
</div>
<!~~ <label for="">Review</label> ~~>
<!~~ </div> ~~>
<!~~ </div> ~~>
</form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary">Submit review</button>
</div>
</div>
</div>
</div>  -->

<!-- Ask for Agency Price modal -->
<!-- <div class="modal fade" id="agency-modal" tabindex="-1" role="dialog" aria-labelledby="agencyModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="text-primary mb-0">Ask for Agency Price</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-primary font-weight-bold">Everest Three Passes Trek</h5>
                <form action="">
                    <div class="form-group icon">
                        <i class="fas fa-user"></i>
                        <input type="text" class="form-control" placeholder="Name">
                    </div>
                    <div class="form-group icon">
                        <i class="fas fa-building"></i>
                        <input type="text" class="form-control" placeholder="Company Name">
                    </div>
                    <div class="form-group icon">
                        <i class="fas fa-link"></i>
                        <input type="url" class="form-control" placeholder="Company Url">
                    </div>
                    <div class="form-group icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" class="form-control" placeholder="Phone No.">
                    </div>
                    <div class="form-group icon">
                        <input type="text" class="form-control" placeholder="Country" list="countries">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="form-group icon">
                        <textarea type="text" rows="5" class="form-control" placeholder="Message"></textarea>
                        <i class="fas fa-comment-alt"></i>
                    </div>
                    <label for="">Review</label> -->
<!-- </div> -->
<!-- </div> -->
<!-- </form> -->
<!-- </div> -->
<!-- <div class="modal-footer">
    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
    <button type="button" class="btn btn-primary">Submit request</button>
</div> -->
</div>

<datalist id="countries">
    <option value="Afghanistan">
    <option value="Albania">
    <option value="Algeria">
    <option value="American Samoa">
    <option value="Andorra">
    <option value="Angola">
    <option value="Anguilla">
    <option value="Antarctica">
    <option value="Antigua and Barbuda">
    <option value="Argentina">
    <option value="Armenia">
    <option value="Aruba">
    <option value="Australia">
    <option value="Austria">
    <option value="Azerbaijan">
    <option value="Bahamas">
    <option value="Bahrain">
    <option value="Bangladesh">
    <option value="Barbados">
    <option value="Belarus">
    <option value="Belgium">
    <option value="Belize">
    <option value="Benin">
    <option value="Bermuda">
    <option value="Bhutan">
    <option value="Bolivia">
    <option value="Bosnia and Herzegovina">
    <option value="Botswana">
    <option value="Bouvet Island">
    <option value="Brazil">
    <option value="British Indian Ocean Territory">
    <option value="Brunei Darussalam">
    <option value="Bulgaria">
    <option value="Burkina Faso">
    <option value="Burundi">
    <option value="Cambodia">
    <option value="Cameroon">
    <option value="Canada">
    <option value="Cape Verde">
    <option value="Cayman Islands">
    <option value="Central African Republic">
    <option value="Chad">
    <option value="Chile">
    <option value="China">
    <option value="Christmas Island">
    <option value="Cocos (Keeling) Islands">
    <option value="Colombia">
    <option value="Comoros">
    <option value="Congo">
    <option value="Congo, The Democratic Republic of The">
    <option value="Cook Islands">
    <option value="Costa Rica">
    <option value="Cote D'ivoire">
    <option value="Croatia">
    <option value="Cuba">
    <option value="Cyprus">
    <option value="Czech Republic">
    <option value="Denmark">
    <option value="Djibouti">
    <option value="Dominica">
    <option value="Dominican Republic">
    <option value="Ecuador">
    <option value="Egypt">
    <option value="El Salvador">
    <option value="Equatorial Guinea">
    <option value="Eritrea">
    <option value="Estonia">
    <option value="Ethiopia">
    <option value="Falkland Islands (Malvinas)">
    <option value="Faroe Islands">
    <option value="Fiji">
    <option value="Finland">
    <option value="France">
    <option value="French Guiana">
    <option value="French Polynesia">
    <option value="French Southern Territories">
    <option value="Gabon">
    <option value="Gambia">
    <option value="Georgia">
    <option value="Germany">
    <option value="Ghana">
    <option value="Gibraltar">
    <option value="Greece">
    <option value="Greenland">
    <option value="Grenada">
    <option value="Guadeloupe">
    <option value="Guam">
    <option value="Guatemala">
    <option value="Guinea">
    <option value="Guinea-bissau">
    <option value="Guyana">
    <option value="Haiti">
    <option value="Heard Island and Mcdonald Islands">
    <option value="Holy See (Vatican City State)">
    <option value="Honduras">
    <option value="Hong Kong">
    <option value="Hungary">
    <option value="Iceland">
    <option value="India">
    <option value="Indonesia">
    <option value="Iran, Islamic Republic of">
    <option value="Iraq">
    <option value="Ireland">
    <option value="Israel">
    <option value="Italy">
    <option value="Jamaica">
    <option value="Japan">
    <option value="Jordan">
    <option value="Kazakhstan">
    <option value="Kenya">
    <option value="Kiribati">
    <option value="Korea, Democratic People's Republic of">
    <option value="Korea, Republic of">
    <option value="Kuwait">
    <option value="Kyrgyzstan">
    <option value="Lao People's Democratic Republic">
    <option value="Latvia">
    <option value="Lebanon">
    <option value="Lesotho">
    <option value="Liberia">
    <option value="Libyan Arab Jamahiriya">
    <option value="Liechtenstein">
    <option value="Lithuania">
    <option value="Luxembourg">
    <option value="Macao">
    <option value="Macedonia, The Former Yugoslav Republic of">
    <option value="Madagascar">
    <option value="Malawi">
    <option value="Malaysia">
    <option value="Maldives">
    <option value="Mali">
    <option value="Malta">
    <option value="Marshall Islands">
    <option value="Martinique">
    <option value="Mauritania">
    <option value="Mauritius">
    <option value="Mayotte">
    <option value="Mexico">
    <option value="Micronesia, Federated States of">
    <option value="Moldova, Republic of">
    <option value="Monaco">
    <option value="Mongolia">
    <option value="Montserrat">
    <option value="Morocco">
    <option value="Mozambique">
    <option value="Myanmar">
    <option value="Namibia">
    <option value="Nauru">
    <option value="Nepal">
    <option value="Netherlands">
    <option value="Netherlands Antilles">
    <option value="New Caledonia">
    <option value="New Zealand">
    <option value="Nicaragua">
    <option value="Niger">
    <option value="Nigeria">
    <option value="Niue">
    <option value="Norfolk Island">
    <option value="Northern Mariana Islands">
    <option value="Norway">
    <option value="Oman">
    <option value="Pakistan">
    <option value="Palau">
    <option value="Palestinian Territory, Occupied">
    <option value="Panama">
    <option value="Papua New Guinea">
    <option value="Paraguay">
    <option value="Peru">
    <option value="Philippines">
    <option value="Pitcairn">
    <option value="Poland">
    <option value="Portugal">
    <option value="Puerto Rico">
    <option value="Qatar">
    <option value="Reunion">
    <option value="Romania">
    <option value="Russian Federation">
    <option value="Rwanda">
    <option value="Saint Helena">
    <option value="Saint Kitts and Nevis">
    <option value="Saint Lucia">
    <option value="Saint Pierre and Miquelon">
    <option value="Saint Vincent and The Grenadines">
    <option value="Samoa">
    <option value="San Marino">
    <option value="Sao Tome and Principe">
    <option value="Saudi Arabia">
    <option value="Senegal">
    <option value="Serbia and Montenegro">
    <option value="Seychelles">
    <option value="Sierra Leone">
    <option value="Singapore">
    <option value="Slovakia">
    <option value="Slovenia">
    <option value="Solomon Islands">
    <option value="Somalia">
    <option value="South Africa">
    <option value="South Georgia and The South Sandwich Islands">
    <option value="Spain">
    <option value="Sri Lanka">
    <option value="Sudan">
    <option value="Suriname">
    <option value="Svalbard and Jan Mayen">
    <option value="Swaziland">
    <option value="Sweden">
    <option value="Switzerland">
    <option value="Syrian Arab Republic">
    <option value="Taiwan, Province of China">
    <option value="Tajikistan">
    <option value="Tanzania, United Republic of">
    <option value="Thailand">
    <option value="Timor-leste">
    <option value="Togo">
    <option value="Tokelau">
    <option value="Tonga">
    <option value="Trinidad and Tobago">
    <option value="Tunisia">
    <option value="Turkey">
    <option value="Turkmenistan">
    <option value="Turks and Caicos Islands">
    <option value="Tuvalu">
    <option value="Uganda">
    <option value="Ukraine">
    <option value="United Arab Emirates">
    <option value="United Kingdom">
    <option value="United States">
    <option value="United States Minor Outlying Islands">
    <option value="Uruguay">
    <option value="Uzbekistan">
    <option value="Vanuatu">
    <option value="Venezuela">
    <option value="Viet Nam">
    <option value="Virgin Islands, British">
    <option value="Virgin Islands, U.S">
    <option value="Wallis and Futuna">
    <option value="Western Sahara">
    <option value="Yemen">
    <option value="Zambia">
    <option value="Zimbabwe">
</datalist>
@endsection
@push('scripts')
<script src="{{ asset('assets/front/js/tour-details.js') }}"></script>
<script>
    jQuery.noConflict(true);
</script>
<script>
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
</script>
<script type="text/javascript">
$(function() {
    function expandAll() {
        alert('h');
    }
    $('#map-modal').on('show.bs.modal', function (e) {
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
            submitHandler: function(form, event) {
                event.preventDefault();
                $(form).find('#redirect-url').val('{!! route("front.trips.show", $trip->slug) !!}');
                if (grecaptcha.getResponse(0)) {
                    var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Sending...');
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }else{
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
        enquiry_captcha = grecaptcha.render('inquiry-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
        // review_captcha = grecaptcha.render('review-g-recaptcha', {'sitekey' : '{!! config("constants.recaptcha.sitekey") !!}'});
    };
</script>
@endpush
