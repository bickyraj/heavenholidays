<?php
  if (session()->has('success_message')) {
    $success_message = session('success_message');
    session()->forget('success_message');
  }
?>
@extends('layouts.admin')
@push('styles')
<link href="./assets/vendors/general/summernote/dist/summernote.css" rel="stylesheet" type="text/css" />
<link href="./assets/vendors/cropperjs/dist/cropper.min.css" rel="stylesheet">
<link href="./assets/vendors/bootstrap-rating-master/bootstrap-rating.css" rel="stylesheet">
@endpush
@section('content')
<div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
    <div class="row">
        <div class="col">
            <!--begin::Portlet-->
            <div class="kt-portlet">
                  <div class="kt-portlet__head">
                      <div class="kt-portlet__head-label">
                        <span class="kt-portlet__head-icon">
                            <i class="kt-font-brand flaticon2-settings"></i>
                        </span>
                          <h3 class="kt-portlet__head-title">
                              Site Settings
                          </h3>
                      </div>
                  </div>
                  <!--begin::Form-->
                  <div class="kt-portlet__body">

                    <ul class="nav nav-tabs trip-nav-tabs" id="tripTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#kt_tabs_1_1">
                                <i class="la la-map-pin"></i> General
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_2">
                                <i class="la la-map-signs"></i> Home Page
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3">
                                <i class="la la-phone"></i> Contact Us
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_4">
                                <i class="la la-share-alt"></i> Get Connected
                            </a>
                        </li>
                    </ul>

                    <div id="trip-tab" class="tab-content trip-tab-form">
                      <div class="tab-pane active" data-index="1" id="kt_tabs_1_1" role="tabpanel">
                        <form class="kt-form" method="POST" action="{{ route('admin.settings.general.store') }}" id="setting-form" enctype="multipart/form-data">
                        {{ csrf_field() }}

                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Site Name </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="site_name" value="{{ Setting::get('site_name') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Email</label>
                            <div class="col-lg-7">
                              <input type="email" id="input-trip-name" class="form-control form-control-sm" name="email" value="{{ Setting::get('email') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Telephone</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="telephone" value="{{ Setting::get('telephone') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Mobile 1</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="mobile1" value="{{ Setting::get('mobile1') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Mobile 2</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="mobile2" value="{{ Setting::get('mobile2') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Address</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="address" value="{{ Setting::get('address') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Office Time</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-office-time" class="form-control form-control-sm" name="office_time" value="{{ Setting::get('office_time') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr>
                          <div class="kt-form__actions">
                            <button type="submit" class="btn btn-sm btn-primary">
                                  <i class="flaticon2-arrow-up"></i>
                                Save</button>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>

                      {{-- home page block --}}
                      <div class="tab-pane" data-index="2" id="kt_tabs_1_2" role="tabpanel">
                        <form class="kt-form" method="POST" action="{{ route('admin.settings.home-page.store') }}" id="setting-home-form" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          {{-- {{ dd(Setting::get('homePage')) }} --}}
                          <h5>Welcome</h5>
                          <hr>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="welcome[title]" value="{{ Setting::get('homePage')['welcome']['title']??'' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Sub Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="welcome[sub_title]" value="{{ Setting::get('homePage')['welcome']['sub_title']??'' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Content</label>
                            <div class="col-lg-7">
                              <input type="hidden" name="welcome[content]">
                              <div id="summernote-home-content" class="summernote"><?= Setting::get('homePage')['welcome']['content']??'' ?></div>
                            </div>
                          </div>
                          <hr>
                          <!-- <h5>Reason</h5>
                            <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="reason[title]" value="{{ Setting::get('homePage')['reason']['title']??'' }}">
                              <span class="form-text text-muted">Please enter your full name</span>
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Content</label>
                            <div class="col-lg-7">
                              <input type="hidden" name="reason[content]">
                              <div id="summernote-reason-content" class="summernote"><?= Setting::get('homePage')['reason']['content']??'' ?></div>
                            </div>
                          </div>
                          <hr> -->
                          <h5>Trip Block 1</h5>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="trip_block_1[title]" value="{{ Setting::get('homePage')['trip_block_1']['title'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr>
                          <h5>Trip Block 2</h5>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="trip_block_2[title]" value="{{ Setting::get('homePage')['trip_block_2']['title'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <h5>Trip Block 3</h5>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="trip_block_3[title]" value="{{ Setting::get('homePage')['trip_block_3']['title'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr>
                          <!-- <h5>Blog Block</h5>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="blog[title]" value="{{ Setting::get('homePage')['blog']['title'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr> -->
                          <h5>Video</h5>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Link </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="video[link]" value="{{ Setting::get('homePage')['video']['link'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          {{-- <div class="form-group row">
                            <pre>{{ Setting::get('homePage') }}</pre>
                            <label class="col-lg-2 col-form-label">Image</label>
                            <div class="col-lg-7">
                              <div class="row">
                                <div class="col-lg-7">
                                  <div class="mb-3">
                                      <img id="cropper-image" class="crop-img-div" src="{{ Setting::getHomePageImage(Setting::get('homePage')['video_image']??null) }}">
                                  </div>
                                </div>
                              </div>
                              <input type="hidden" id="cropped-data-input" name="cropped_data">
                              <input type="file" name="file" id="cropper-upload">
                              <span class="form-text text-muted">Please enter your full name</span>
                            </div>
                          </div> --}}

                          <hr>
                          <div class="kt-form__actions">
                            <button type="submit" id="home-page-save-btn" class="btn btn-sm btn-primary">
                                  <i class="flaticon2-arrow-up"></i>
                                Save</button>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>
                      {{-- end of home page block --}}

                      {{-- Contact us block --}}
                      <div class="tab-pane" data-index="3" id="kt_tabs_1_3" role="tabpanel">
                        <form class="kt-form" method="POST" action="{{ route('admin.settings.contact-us.store') }}" id="setting-contact-us-form" enctype="multipart/form-data">
                          {{ csrf_field() }}
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Title </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-trip-name" class="form-control form-control-sm" name="title" value="{{ Setting::get('contactUs')['title'] ?? '' }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Content </label>
                            <div class="col-lg-7">
                              <textarea name="content" class="form-control" id="" cols="30" rows="10">{{ Setting::get('contactUs')['content'] ?? '' }}</textarea>
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Map Iframe</label>
                            <div class="col-lg-7">
                              <textarea name="map" class="form-control" id="" cols="30" rows="10">{{ Setting::get('contactUs')['map'] ?? '' }}</textarea>
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr>
                          <div class="kt-form__actions">
                            <button type="submit" class="btn btn-sm btn-primary">
                                  <i class="flaticon2-arrow-up"></i>
                                Save</button>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>
                      {{-- end of contact us --}}

                      {{-- get connected block --}}
                      <div class="tab-pane" data-index="4" id="kt_tabs_1_4" role="tabpanel">
                        <form class="kt-form" method="POST" action="{{ route('admin.settings.socialmedia.store') }}" id="setting-form">
                        {{ csrf_field() }}

                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Pinterest </label>
                            <div class="col-lg-7">
                              <input type="text" id="input-pinterest" class="form-control form-control-sm" name="pinterest" value="{{ Setting::get('pinterest') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Facebook</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-facebook" class="form-control form-control-sm" name="facebook" value="{{ Setting::get('facebook') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Instagram</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-instagram" class="form-control form-control-sm" name="instagram" value="{{ Setting::get('instagram') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Twitter</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-twitter" class="form-control form-control-sm" name="twitter" value="{{ Setting::get('twitter') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Flicker</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-flicker" class="form-control form-control-sm" name="flicker" value="{{ Setting::get('flicker') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">What's App</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-whatsapp" class="form-control form-control-sm" name="whatsapp" value="{{ Setting::get('whatsapp') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <div class="form-group row">
                            <label class="col-lg-2 col-form-label">Viber</label>
                            <div class="col-lg-7">
                              <input type="text" id="input-viber" class="form-control form-control-sm" name="viber" value="{{ Setting::get('viber') }}">
                              {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                            </div>
                          </div>
                          <hr>
                          <div class="kt-form__actions">
                            <button type="submit" class="btn btn-sm btn-primary">
                                  <i class="flaticon2-arrow-up"></i>
                                Save</button>
                            <a href="{{ route('admin.settings.general') }}" class="btn btn-secondary">Cancel</a>
                          </div>
                        </form>
                      </div>
                      {{-- end of get connected block --}}
                    </div>
                  </div>
                  <!-- <div class="kt-portlet__foot">
                      <div class="kt-form__actions">
                          {{-- <button type="submit" class="btn btn-success">Publish</button> --}}
                          {{-- <button type="reset" class="btn btn-secondary">Cancel</button> --}}
                          <button type="submit" class="btn btn-sm btn-primary">
                                <i class="flaticon2-arrow-up"></i>
                              Publish</button>
                      </div>
                  </div> -->
                <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="./assets/vendors/general/summernote/dist/summernote.js" type="text/javascript"></script>
<script src="./assets/js/demo1/pages/crud/forms/widgets/summernote.js" type="text/javascript"></script>
<script src="./assets/vendors/cropperjs/dist/cropper.min.js"></script>
<script src="./assets/vendors/jquery-validation/dist/jquery.validate.min.js"></script>
<script src="./assets/vendors/jquery-validation/dist/additional-methods.min.js"></script>
<script src="./assets/vendors/bootstrap-rating-master/bootstrap-rating.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
$(function() {
  var success_message = '{{ $success_message ?? '' }}';
  if (success_message) {
    Toast.fire({
      type: 'success',
      title: success_message
    })
  }

    var validation_rules = {
        pdf_file_name: {
          extension: "pdf"
        },
        map_file_name: {
          extension: "jpeg|jpg|png|gif"
        },
        "trip_seo[og_image]": {
          extension: "jpeg|jpg|png|gif"
        },
        cost: {
          number: true
        },
        max_altitude: {
          number: true
        },
        offer_price: {
          number: true
        }
    };
    var validation_messages = {
      pdf_file_name: {
          extension: "Only pdf is allowed."
      },
      map_file_name: {
          extension: "Only image files is allowed."
      }
    };

    $('#setting-home-form button[type="submit"]').on('click', function(event) {
      event.preventDefault();
      $('input[name="welcome[content]"]').val($('#summernote-home-content').summernote('code'));
      $('input[name="reason[content]"]').val($('#summernote-reason-content').summernote('code'));
      $("#setting-home-form").submit();
    });

    function handleTripAddForm(form, cont = false) {

      var form = $(form);
      var formData = new FormData(form[0]);

      if (!$('#tripTab li:nth-child(2) a').hasClass("disabled")) {
        var trip_info = "";

        trip_info = {
          'accomodation' : $('#summernote-accomodation').summernote('code'),
          'meals' : $('#summernote-meals').summernote('code'),
          'transportation' : $('#summernote-transportation').summernote('code'),
          'overview' : $('#summernote-overview').summernote('code'),
          'highlights' : $('#summernote-highlights').summernote('code')
        };

        formData.append('trip_info', JSON.stringify(trip_info));
      }

      if (!$('#tripTab li:nth-child(3) a').hasClass("disabled")) {
        var trip_include = "";

        trip_include = {
          'include' : $('#summernote-include').summernote('code'),
          'exclude' : $('#summernote-exclude').summernote('code'),
        };

        formData.append('trip_include_exclude', JSON.stringify(trip_include));
      }

      if (!$('#tripTab li:nth-child(4) a').hasClass("disabled")) {
        var trip_leader = $('#summernote-leader').summernote('code');
        formData.append('trip_seo[about_leader]', trip_leader);
      }

      if (!$('#tripTab li:nth-child(5) a').hasClass("disabled")) {

        $.each($("#itinerary-block>.itinerary-group").find('.summernote'), function(i, v) {
          var desc = $(v).summernote('code');
          formData.append('trip_itineraries['+i+'][day]', i + 1);
          formData.append('trip_itineraries['+i+'][display_order]', i + 1);
          formData.append('trip_itineraries['+i+'][description]', desc);
        });
      }

      $.ajax({
          url: "{{ route('admin.trips.store') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
              if (res.status === 1) {
                if (cont) {
                  if (typeof(Storage) !== "undefined") {
                    // Store
                    sessionStorage.setItem("save-and-continue", true);
                  }

                  location.href = '{{ url('/admin/trips/edit') }}' + '/' + res.trip.id ;
                } else {
                  location.href = '{{ route('admin.trips.index') }}';
                }
              }
          }
      });
    }

    function initSummerNote() {
      $('#summernote-home-content').summernote();
      $('#summernote-reason-content').summernote();
    }

    var cropped = false;
    const image = document.getElementById('cropper-image');
    var cropper = "";

    function handleBannerAddForm(form) {
      var form = $(form);
      var formData = new FormData(form[0]);
      if (cropper) {
        formData.append('cropped_data', JSON.stringify(cropper.getData()));
      }
    }

    $("#home-page-save-btn").on('click', function(event) {
      event.preventDefault();
      if (cropper) {
        $("#cropped-data-input").val(JSON.stringify(cropper.getData()));
      }
      $(this).closest('form').submit();
    });

    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
          $('#cropper-image').attr('src', e.target.result);
          initCropperjs();
        }

        reader.readAsDataURL(input.files[0]);
      }
    }

    $("#cropper-upload").change(function() {
      readURL(this);
    });

    function initCropperjs() {
      if (cropped) {
        cropper.destroy();
        cropped = false;
      }

      cropper = new Cropper(image, {
          aspectRatio: 2 / 1,
          zoomable: false,
          viewMode: 2,
          ready: function (data) {
            var contData = cropper.getImageData(); //Get container data
            cropper.setCropBoxData({"left":0,"top":0,"width":contData.width,"height":contData.height});
          },
          crop(event) {
              // console.log(event.detail.x);
              // console.log(event.detail.y);
              // console.log(event.detail.width);
              // console.log(event.detail.height);
              // console.log(event.detail.rotate);
              // console.log(event.detail.scaleX);
              // console.log(event.detail.scaleY);
          },
      });

      cropped = true;
    }

    initCropperjs();
    initSummerNote();
});

</script>
@endpush
