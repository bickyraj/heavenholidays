@php
    if (request()->has('destination_id')) {
        $get_destination_id = request('destination_id');
    }

    if (request()->has('keyword')) {
        $get_keyword = request('keyword');
    }

    if (request()->has('activity_id')) {
        $get_activity_id = request('activity_id');
    }

    if (request()->has('price')) {
        $get_price = request('price');
    }

    if (request()->has('duration')) {
        $get_duration = request('duration');
    }

    if (request()->has('page')) {
        $get_page = request('page');
    }
@endphp

@extends('layouts.front_inner')
@push('styles')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/front/css/front-search-slider.css') }}">
@endpush
@section('content')
    <!-- Hero -->
    <section class="hero hero-alt relative">
        <img src="{{ asset('assets/front/img/hero.jpg') }}" alt="">
        <div class="overlay absolute">
            <div class="container ">
                <h1>Tour Packages</h1>
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb fs-sm wrap">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tour Packages</li>
                        </ol>
                    </nav>
                </div>
            </div>
    </section>

    <section class="pt-5">
        <div class="container">
            <div class="mb-4" id="searchDiv">
                <div class="grid lg:grid-cols-5 gap-2">
                    <div class="col-lg-2 col-md-2 col-sm-2">
                        <div class="form-group">
                            <label for="">Keywords</label>
                            <input type="text" id="keyword" class="form-control" value="{{ $get_keyword ?? '' }}"
                                name="keyword" />
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Destinations</label>
                            <select name="" id="select-destination" class="custom-select">
                                <option value="" selected>All Destinations</option>
                                @if ($destinations)
                                    @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}"
                                            {{ isset($get_destination_id) && $get_destination_id == $destination->id ? 'selected' : '' }}>
                                            {{ $destination->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="">Activities</label>
                            <select name="" id="select-activity" class="custom-select">
                                <option value="" selected>All activities</option>
                                @if ($activities)
                                    @foreach ($activities as $activity)
                                        <option value="{{ $activity->id }}"
                                            {{ isset($get_activity_id) && $get_activity_id == $activity->id ? 'selected' : '' }}>
                                            {{ $activity->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"
                            style="border-left: 1px solid #ededed; padding-left: 19px; margin-left: 12px;">
                            <label for="">Duration</label>
                            <div class="custom-slider-container">
                                <div id="duration-slider-range"></div>
                                <input class="price-range-input" type="text" id="trip-days" readonly
                                    style="border:0; color:black; font-size:16px;" value="1 days - 30 days">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group"
                            style="border-left: 1px solid #ededed; padding-left: 19px; margin-left: 12px;">
                            <label for="">Price Range</label>
                            <div class="custom-slider-container">
                                <div id="slider-range"></div>
                                <input class="price-range-input" type="text" id="amount" readonly
                                    style="border:0; color:black; font-size:16px;" value="$0 - $100000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-light">
            <div class="container py-4">
                <div id="tirps-block" class="grid md:grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-8">
                </div>
            </div>
            <div class="flex items-center" style="justify-content: center; margin-top: 50px;">
                <div id="spinner-block"></div>
                <button id="show-more" class="btn btn-accent" style="display: block; margin-bottom: 50px;">show
                    more</button>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function() {
            let xhr;
            let typingTimer;
            const debounceTime = 500;
            let totalPage;
            let nextPage;
            let currentPage = `{{ (isset($get_page) && !empty($get_page))? $get_page: 1  }}`;

            function initSlider() {
                $("#duration-slider-range").slider({
                    classes: {
                        "ui-slider": "custom-slider"
                    },
                    range: true,
                    min: 1,
                    max: 30,
                    values: [1, 30],
                    change: function(event, ui) {
                        performSearch();
                    },
                    slide: function(event, ui) {
                        currentPage = 1;
                        $("#trip-days").val(ui.values[0] + " days - " + ui.values[1] + " days");
                    }
                });

                $("#slider-range").slider({
                    classes: {
                        "ui-slider": "custom-slider"
                    },
                    range: true,
                    min: 0,
                    max: 100000,
                    values: [0, 100000],
                    change: function(event, ui) {
                        performSearch();
                    },
                    slide: function(event, ui) {
                        currentPage = 1;
                        $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                    }
                });

                const duration = `{{ $get_duration ?? '' }}`;
                if (duration) {
                    const duration_arr = duration.split(",");
                    $("#trip-days").val(duration_arr[0] + " days - " + duration_arr[1] + " days");
                    $("#duration-slider-range").slider("values", duration_arr);
                }

                const price = `{{ $get_price ?? '' }}`;
                if (price) {
                    const price_arr = price.split(",");
                    $("#amount").val("$" + price_arr[0] + " - $" + price_arr[1]);
                    $("#slider-range").slider("values", price_arr);
                }
            }

            initSlider();

            $("select").on('change', function(event) {
                event.preventDefault();
                performSearch();
            });

            // $('html, body').animate({
            //     scrollTop: $("#searchDiv").offset().top
            // }, "fast");

            $("#show-more").on('click', async function(event) {
                event.preventDefault();
                if (nextPage) {
                    currentPage++;
                    await paginate(currentPage);
                    if (!nextPage) {
                        $("#show-more").hide();
                    }
                }
            });

            $("#keyword").on('keyup', function(event) {
                handleKeyDown();
            });

            function handleKeyDown() {
                currentPage = 1;
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, debounceTime);
            }

            function performSearch() {
                if (xhr && xhr.readyState !== 4) {
                    // If there is an ongoing AJAX request, abort it
                    xhr.abort();
                }
                filter();
            }

            async function paginate(page) {
                return new Promise((resolve, reject) => {
                    const keyword = $("#keyword").val();
                    const amount = $("#slider-range").slider("values");
                    const duration = $("#duration-slider-range").slider("values");
                    var destination_id = $("#select-destination").val();
                    var activity_id = $("#select-activity").val();
                    var url_query =
                        `page=${currentPage}&keyword=${keyword}&destination_id=${destination_id}&activity_id=${activity_id}&price=${amount}&duration=${duration}`;
                    var url = "{{ url('trips/filter') }}" + `?${url_query}`;
                    $.ajax({
                        url: url,
                        type: "GET",
                        dataType: "json",
                        async: "false",
                        beforeSend: function(xhr) {
                            var spinner = '<button style="margin:0 auto;" class="btn btn-sm btn-primary text-white" type="button" disabled>\
                                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>\
                                                                    Loading Trips...\
                                                                    </button>';
                            $("#spinner-block").html(spinner);
                            $("#show-more").hide();
                        },
                        success: function(res) {
                            if (res.success) {
                                $("#tirps-block").append(res.data);
                                nextPage = res.pagination.next_page;
                            }
                        }
                    }).done(function(data) {
                        $("#spinner-block").html('');
                        $("#show-more").show();
                        window.history.pushState({}, "", "{!! route('front.trips.listing') !!}?" +
                        url_query);
                        resolve(true);
                    });
                });
            }

            performSearch();

            function filter() {
                const keyword = $("#keyword").val();
                const amount = $("#slider-range").slider("values");
                const duration = $("#duration-slider-range").slider("values");
                var destination_id = $("#select-destination").val();
                var activity_id = $("#select-activity").val();
                var url_query =
                    `page=${currentPage}&keyword=${keyword}&destination_id=${destination_id}&activity_id=${activity_id}&price=${amount}&duration=${duration}`;
                var url = "{{ url('trips/filter') }}" + `?${url_query}`;
                xhr = $.ajax({
                    url: url,
                    type: "GET",
                    dataType: "json",
                    //data: data,
                    async: "false",
                    beforeSend: function(xhr) {
                        var spinner = '<button style="margin:0 auto;" class="btn btn-sm btn-primary text-white" type="button" disabled>\
                                                      <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>\
                                                      Loading Trips...\
                                                    </button>';
                        $("#spinner-block").html(spinner);
                        $("#show-more").hide();
                    },
                    success: function(res) {
                        if (res.success) {
                            $("#tirps-block").html(res.data);
                            totalPage = res.pagination.total;
                            currentPage = res.pagination.current_page;
                            nextPage = res.pagination.next_page;
                        }
                    }
                }).done(function(data) {
                    $("#spinner-block").html('');
                    if (!nextPage) {
                        $("#show-more").hide();
                    } else {

                        $("#show-more").show();
                    }
                    window.history.pushState({}, "", "{!! route('front.trips.listing') !!}?" + url_query);
                });
            }
        });
    </script>
@endpush
