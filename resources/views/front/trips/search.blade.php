@extends('layouts.front_inner')
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
        <div class="lim mb-4">
            <p>
                Lorem ipsum, dolor sit amet consectetur adipisicing elit. Dolor voluptas expedita aspernatur voluptates
                laudantium natus deleniti nobis. Quos quibusdam hic perferendis incidunt dolorem inventore odit officiis impedit
                optio, distinctio rerum praesentium quod fugiat ab a maiores similique eius, quia placeat? Vel sequi quo omnis?
                Velit deleniti natus odio similique eius.
            </p>
        </div>
        <div class="mb-4" id="searchDiv">
            <div class="grid lg:grid-cols-3 gap-2">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Destinations</label>
                        <select name="" id="select-destination" class="custom-select">
                          <option value="" selected>All Destinations</option>
                          @if($destinations)
                            @foreach($destinations as $destination)
                            <option value="{{ $destination->id }}">{{ $destination->name }}</option>
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
                          @if($activities)
                            @foreach($activities as $activity)
                            <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                            @endforeach
                          @endif
                        </select>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="">Sort by</label>
                        <select name="" id="" class="custom-select">
                            <option value="">Price (low to high)</option>
                            <option value="">Price (high to low)</option>
                            <option value="">Ratings (low to high)</option>
                            <option value="" selected>Ratings (high to low)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Results -->
    </div>
    <div class="bg-light">
        <div class="container py-4">
            @if(isset($keyword) && !empty($keyword))
            <p id="search-p" class="fs-sm">Search results for "<strong>{{ strtoupper($keyword) }}</strong>"</p>
            @endif


            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-2 xl:gap-3">
                <?php foreach ($trips as $tour) : ?>
                    @include('front.elements.tour-card')
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
@endsection
@push('scripts')
<script type="text/javascript">
    $('html, body').animate({
        scrollTop: $("#searchDiv").offset().top
    }, "fast");

  $(".custom-select").on('change', function(event) {
    filter();
  });

  function filter() {
    var destination_id = $("#select-destination").val();
    var activity_id = $("#select-activity").val();
    var sortBy = $("#select-sort").val();
    var url_query = "dest=" + destination_id + "&act=" + activity_id + "&price=" + sortBy;

    var filter_url = '{{ route("front.trips.search") }}' + '?' + url_query;
    window.location.href = filter_url;

    /*$.ajax({
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
        $("#tirps-block").html(spinner);
      },
      success: function(res) {
        if (res.success) {
          $("#search-p").hide();
          if (keyword == "") {
            window.history.pushState({}, document.title, "/" + "trips");
          }
          $("#tirps-block").html(res.data);
          keyword = "";
        }
      }
    }).done(function( data ) {
      // console.log('done');
    });*/

  }
</script>
@endpush
