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
                            <i class="kt-font-brand flaticon-edit-1"></i>
                        </span>
                          <h3 class="kt-portlet__head-title">
                              Edit Trip
                          </h3>
                      </div>
                      <div class="kt-form__actions mt-3">
                          <a href="{{ route('admin.trips.index') }}" class="btn btn-sm btn-secondary">Cancel</a>
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
                                  <i class="la la-map-signs"></i> Trip Info
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_3">
                                  <i class="la la-list-alt"></i> Package Includes/Excludes
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_4">
                                  <i class="la la-magic"></i> Meta
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_5">
                                  <i class="la la-list-ul"></i> Itinerary
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link" data-toggle="tab" href="#kt_tabs_1_6">
                                  <i class="la la-file-image-o"></i> Sliders
                              </a>
                          </li>
                      </ul>


                      <div id="trip-tab" class="tab-content trip-tab-form">
                          {{-- Trip --}}
                          <div class="tab-pane active" data-index="1" id="kt_tabs_1_1" role="tabpanel">
                            <form class="kt-form" id="edit-trip-form" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{ $trip->id }}">
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Trip Name</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" name="name" value="{{ $trip->name }}" required>
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Trip Location</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" name="location" value="{{ $trip->location }}">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Trip Code</label>
                                <div class="col-lg-7">
                                  <input type="text" id="input-trip-name" value="{{ $trip->trip_code }}" class="form-control form-control-sm" name="trip_code">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Trip Duration (in days)</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->duration }}" name="duration">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Maximum Altitude(in meters)</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->max_altitude }}" name="max_altitude">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Group Size</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->group_size }}" name="group_size">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Cost</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->cost }}" name="cost">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Best Value</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->best_value }}" name="best_value">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Offer Price</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->offer_price }}" name="offer_price">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Difficulty Grade</label>
                                <div class="col-lg-7">
                                  <select name="difficulty_grade" class="custom-select form-control form-control-sm">
                                      <option value="" selected="">--Select Difficulty Grade--</option>
                                      <option value="1" <?php echo (($trip->difficulty_grade == 1)?'selected': '');?>>Beginner</option>
                                      <option value="2" <?php echo (($trip->difficulty_grade == 2)?'selected': '');?>>Easy</option>
                                      <option value="3" <?php echo (($trip->difficulty_grade == 3)?'selected': '');?>>Moderate</option>
                                      <option value="4" <?php echo (($trip->difficulty_grade == 4)?'selected': '');?>>Difficult</option>
                                      <option value="5" <?php echo (($trip->difficulty_grade == 5)?'selected': '');?>>Advance</option>
                                  </select>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Starting Point</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->starting_point }}" name="starting_point">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Ending Point</label>
                                <div class="col-lg-7">
                                  <input type="text" class="form-control form-control-sm" value="{{ $trip->ending_point }}" name="ending_point">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Destination</label>
                                <div class="col-lg-7">
                                  <select class="custom-select form-control form-control-sm" name="destination_id">
                                      <option selected="" value="">--Select Destination--</option>
                                      @foreach($destinations as $country)
                                      <option value="{{ $country->id }}" <?= ($trip->destination && ($trip->destination->id == $country->id)? 'selected': '') ?>>{{ $country->name }}</option>
                                      @endforeach
                                  </select>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Region</label>
                                <div class="col-lg-7">
                                  <select class="custom-select form-control form-control-sm" name="region_id">
                                      <option selected="" value="">--Select Region--</option>
                                      @foreach($regions as $region)
                                      <option value="{{ $region->id }}" <?= ($trip->region && ($trip->region->id == $region->id)? 'selected': '') ?>>{{ $region->name }}</option>
                                      @endforeach
                                  </select>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label >Activities</label>
                                <div class="kt-checkbox-list">
                                  @if(iterator_count($activities))
                                    @foreach($activities as $activity)
                                    <label class="kt-checkbox kt-checkbox--brand">
                                      <input type="checkbox" name="activities[]" <?php echo ((in_array($activity->id, $activity_ids)?'checked': '')); ?> value="{{ $activity->id }}"> {{ $activity->name }}
                                      <span></span>
                                    </label>
                                    @endforeach
                                  @else
                                    <p>No activity added.</p>
                                  @endif
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label >Choose Similar Trips</label>
                                <div class="kt-checkbox-list">
                                  @if(iterator_count($trips))
                                    @foreach($trips as $item_trip)
                                    <label class="kt-checkbox kt-checkbox--brand">
                                      <input type="checkbox" name="similar_trips[]" <?php echo ((in_array($item_trip->id, $similar_trip_ids)?'checked': '')); ?> value="{{ $item_trip->id }}"> {{ $item_trip->name }}
                                      <span></span>
                                    </label>
                                    @endforeach
                                  @else
                                    <p>No trips added.</p>
                                  @endif
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label >Choose Addon Trips</label>
                                <div class="kt-checkbox-list">
                                  @if(iterator_count($trips))
                                    @foreach($trips as $item_trip)
                                    <label class="kt-checkbox kt-checkbox--brand">
                                      <input type="checkbox" name="addon_trips[]" <?php echo ((in_array($item_trip->id, $addon_trip_ids)?'checked': '')); ?> value="{{ $item_trip->id }}"> {{ $item_trip->name }}
                                      <span></span>
                                    </label>
                                    @endforeach
                                  @else
                                    <p>No trips added.</p>
                                  @endif
                                </div>
                              </div>
                              <hr>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Map File</label>
                                <div class="col-lg-7">
                                  <div>
                                    <p><span id="map_file_name">{!! ($trip->map_original_file_name)? $trip->map_original_file_name: 'choose file' !!}</span><button id="remove-map-file-btn" class="btn btn-sm text-danger"><i class="fa fa-times"></i></button></p>
                                  </div>
                                  <div>
                                    <button type="button" class="btn btn-sm btn-secondary btn-wide" onclick="document.getElementById('map_file').click();"> Upload Map Image
                                    </button>
                                  </div>
                                  <input type="hidden" name="has_map_file" id="has_map_file" value="{{ ($trip->pdf_original_file_name)?1:0 }}">
                                  <input type="file" style="display: none;" id="map_file" class="form-control form-control-sm" name="map_file_name">
                                </div>
                              </div>
                              <hr>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">PDF File</label>
                                <div class="col-lg-7">
                                  <div>
                                    <input type="hidden" name="has_pdf_file" id="has_pdf_file" value="{{ ($trip->pdf_original_file_name)?1:0 }}">
                                    <p><span id="pdf_file_name">{!! ($trip->pdf_original_file_name)? $trip->pdf_original_file_name: 'choose file' !!}</span><button id="remove-pdf-file-btn" class="btn btn-sm text-danger"><i class="fa fa-times"></i></button></p>
                                  </div>
                                  <div>
                                    <input type="button" class="btn btn-sm btn-secondary btn-wide" value="Upload Pdf" onclick="document.getElementById('pdf_file').click();" />
                                  </div>
                                  <input type="file" style="display: none;" id="pdf_file" class="form-control form-control-sm" name="pdf_file_name">
                                </div>
                              </div>
                              <hr>
                              <div class="form-group row">
                                <label class="col-2 col-form-label">Rating</label>
                                <div class="col-3">
                                  <input type="hidden" id="trip-rating" name="rating" value="{{ $trip->rating }}" class="rating" data-filled="fas fa-star fa-2x" data-empty="far fa-star fa-2x"/>
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-2 col-form-label">Show/Hide</label>
                                <div class="col-3">
                                  <span class="kt-switch kt-switch--sm kt-switch--icon">
                                  <label>
                                  <input type="checkbox" checked="<?php (($trip->show_status == 1)?'checked':''); ?>" name="show_status">
                                  <span></span>
                                  </label>
                                  </span>
                                </div>
                              </div>
                              <hr>
                              <div class="kt-form__actions">
                                <!-- <button type="submit" class="btn btn-sm btn-primary">
                                      <i class="flaticon2-arrow-up"></i>
                                    Update</button> -->
                                <button type="submit" class="btn btn-sm btn-success">
                                  Update and Continue <i class="la la-long-arrow-right"></i></button>
                              </div>
                            </form>
                          </div>

                          {{-- Trip Info --}}
                          <div class="tab-pane" data-index="2" id="kt_tabs_1_2" role="tabpanel">
                            <form class="kt-form" id="edit-trip-info-form" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{ $trip->id }}">
                              <div class="form-group">
                                <label class="form-label">Trip Route</label>
                                <textarea class="form-control" name="trip_route" id="trip-route" cols="30" rows="5">{!! ($trip->trip_info)?$trip->trip_info->trip_route:'' !!}</textarea>
                              </div>
                              <div class="form-group">
                                <label class="form-label">Best Season</label>
                                <input type="text" name="best_season" id="best-season-text" class="form-control" value="{{ $trip->trip_info->best_season??'' }}">
                              </div>
                              <div class="form-group">
                                <label class="form-label">Accomodation</label>
                                <div id="summernote-accomodation" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->accomodation:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Meals</label>
                                <div id="summernote-meals" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->meals:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Transportation</label>
                                <div id="summernote-transportation" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->transportation:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Overview</label>
                                <div id="summernote-overview" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->overview:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Highlights</label>
                                <div id="summernote-highlights" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->highlights:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Important Note</label>
                                <div id="summernote-important-note" class="summernote">
                                  <?= (($trip->trip_info)?$trip->trip_info->important_note:'');?>
                                </div>
                              </div>
                              <hr>
                              <div class="kt-form__actions">
                                <button type="submit" class="btn btn-sm btn-success">
                                  Update and Continue <i class="la la-long-arrow-right"></i>
                                </button>
                              </div>
                            </form>
                          </div>

                          {{-- Package includes and excludes --}}
                          <div class="tab-pane" data-index="3" id="kt_tabs_1_3" role="tabpanel">
                            <form class="kt-form" id="edit-trip-includes-form" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{ $trip->id }}">
                              <div class="form-group">
                                <label class="form-label">Include</label>
                                <div id="summernote-include" class="summernote">
                                  <?= (($trip->trip_include_exclude)?$trip->trip_include_exclude->include:''); ?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Exclude</label>
                                <div id="summernote-exclude" class="summernote">
                                  <?= (($trip->trip_include_exclude)?$trip->trip_include_exclude->exclude:''); ?>
                                </div>
                              </div>
                              <hr>
                              <div class="form-group">
                                <label class="form-label">Equipment Checklist</label>
                                <div id="summernote-complimentary" class="summernote">
                                  <?= (($trip->trip_include_exclude)?$trip->trip_include_exclude->complimentary:''); ?>
                                </div>
                              </div>
                              <hr>
                              <div class="kt-form__actions">
                                <!-- <button type="submit" class="btn btn-sm btn-primary">
                                      <i class="flaticon2-arrow-up"></i>
                                    Update</button> -->
                                <button type="submit" class="btn btn-sm btn-success">
                                  Update and Continue <i class="la la-long-arrow-right"></i></button>
                              </div>
                            </form>
                          </div>

                          {{-- Meta --}}
                          <div class="tab-pane" data-index="4" id="kt_tabs_1_4" role="tabpanel">
                            <form class="kt-form" id="edit-trip-meta-form" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{ $trip->id }}">
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Canonical Url</label>
                                <div class="col-lg-7">
                                  <input type="text" id="input-trip-name" value="{!! !empty($trip->trip_seo) ? $trip->trip_seo->canonical_url : '' !!}" class="form-control form-control-sm" name="trip_seo[canonical_url]">
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Meta Title</label>
                                <div class="col-lg-7">
                                  <textarea name="trip_seo[meta_title]" class="form-control form-control-sm" id="" cols="30" rows="2">{!! !empty($trip->trip_seo) ? $trip->trip_seo->meta_title : '' !!}</textarea>
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Meta Keywords</label>
                                <div class="col-lg-7">
                                  <textarea name="trip_seo[meta_keywords]" class="form-control form-control-sm" id="" cols="30" rows="2">{!! !empty($trip->trip_seo) ? $trip->trip_seo->meta_keywords : '' !!}</textarea>
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Og Image</label>
                                <div class="col-lg-7">
                                  <div>
                                    <p id="og_file_name">{!! !empty($trip->trip_seo) ? $trip->trip_seo->og_image : '' !!}</p>
                                  </div>
                                  <div>
                                    <button type="button" class="btn btn-sm btn-secondary btn-wide" onclick="document.getElementById('og_file').click();"> Upload Og Image
                                    </button>
                                  </div>
                                  <input type="file" style="display: none;" id="og_file" class="form-control form-control-sm" name="trip_seo[og_image]">
                                </div>
                              </div>
                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Meta Description</label>
                                <div class="col-lg-7">
                                  <textarea name="trip_seo[meta_description]" class="form-control form-control-sm" id="" cols="30" rows="2">{!! !empty($trip->trip_seo) ? $trip->trip_seo->meta_description : '' !!}</textarea>
                                  {{-- <span class="form-text text-muted">Please enter your full name</span> --}}
                                </div>
                              </div>

                              <div class="form-group row">
                                <label class="col-lg-2 col-form-label">About Leader</label>
                                <div class="col-lg-7">
                                  <div id="summernote-leader" class="summernote">
                                    <?= (($trip->trip_seo)?$trip->trip_seo->about_leader:''); ?>
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class="kt-form__actions">
                                <!-- <button type="submit" class="btn btn-sm btn-primary">
                                      <i class="flaticon2-arrow-up"></i>
                                    Update</button> -->
                                <button type="submit" class="btn btn-sm btn-success">
                                  Update and Continue <i class="la la-long-arrow-right"></i></button>
                              </div>
                            </form>
                          </div>

                          {{-- Itinerary --}}
                          <div class="tab-pane" data-index="5" id="kt_tabs_1_5" role="tabpanel">
                            <form class="kt-form" id="edit-trip-itinerary-form" enctype="multipart/form-data">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{ $trip->id }}">
                              <div class="row">
                                <div class="col-lg-9 mb-5">
                                  <div class="row mb-3">
                                    <div class="col">
                                      <button id="add-itinerary-btn" class="btn btn-sm btn-outline-brand pull-right"><i class="flaticon2-plus"></i> Add Itinerary</button>
                                    </div>
                                  </div>

                                  <div id="itinerary-block" data-n="1">
                                      @include('admin.trips.edit-itinerary-form')
                                  </div>
                                </div>
                              </div>
                              <hr>
                              <div class="kt-form__actions">
                                <!-- <button type="submit" class="btn btn-sm btn-primary">
                                      <i class="flaticon2-arrow-up"></i>
                                    Update</button> -->
                                <button type="submit" class="btn btn-sm btn-success">
                                  Update and Continue <i class="la la-long-arrow-right"></i></button>
                              </div>
                            </form>
                          </div>

                          {{-- Galleries --}}
                          <div class="tab-pane" data-index="6" id="kt_tabs_1_6" role="tabpanel">
                            <div class="mb-3">
                              <button type="button" class="btn btn-bold btn-label-brand btn-sm pull-right" data-backdrop="static" data-toggle="modal" data-target="#kt_modal_4">Add Image</button>
                            </div>
                            <table id="gallery-table" class="table table-striped">
                                <thead>
                                  <tr>
                                      <th>#</th>
                                      <th>Image</th>
                                      <th>Caption</th>
                                      <th>Alt Tag</th>
                                      <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @if(iterator_count($trip->trip_galleries))
                                    @foreach($trip->trip_galleries as $key => $gallery)
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td><img style="tbl-img" src="{{ $gallery->thumbImageUrl }}"></td>
                                        <td>{!! $gallery->caption? $gallery->caption:'--' !!}</td>
                                        <td>{!! $gallery->alt_tag? $gallery->alt_tag:'--' !!}</td>
                                        <td>
                                            <a href="{{ route('admin.trips.slider.edit', ['sliderId' => $gallery->id]) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit Image">
                                                <i class="la la-edit"></i>
                                            </a>
                                          <button class="btn btn-sm btn-clean btn-icon btn-icon-md kt_sweetalert_delete_image" data-id="{{ $gallery->id }}" title="Delete">
                                            <i class="la la-trash"></i>
                                          </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                  @else
                                  <tr>
                                      <td colspan="5">No galleries.</td>
                                  </tr>
                                  @endif
                                </tbody>
                            </table>
                          </div>
                      </div>
                  </div>
                  <!-- <hr>
                  <div class="kt-portlet__foot">
                      <div class="kt-form__actions">
                        <div class="kt-form__actions">
                          <button type="submit" class="btn btn-sm btn-primary">
                                <i class="flaticon2-arrow-up"></i>
                              Update</button>
                          <button id="form-continue" class="btn btn-sm btn-success">
                            Save and Continue <i class="la la-long-arrow-right"></i></button>
                        </div>
                      </div>
                  </div> -->
                <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>
</div>
<!--begin::Modal-->
<div class="modal fade" id="kt_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form class="kt-form" id="add-image" enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="modal-body">
                <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                <div class="form-group">
                    <label for="">Page Image</label>
                    <div class="row">
                      <div class="col-lg-7">
                        <div class="mb-3">
                            <img id="cropper-image" class="crop-img-div" src="{{ asset('img/default.gif') }}">
                        </div>
                        <input type="file" name="file" style="display: block;" accept="image/x-png,image/gif,image/jpeg" id="cropper-upload">
                      </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Caption</label>
                    <input type="text" name="caption" class="form-control">
                </div>
                <div class="form-group">
                    <label for="recipient-name" class="form-control-label">Alt Tag</label>
                    <input type="text" name="alt_tag" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary">Upload</button>
              </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
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
  $("#trip-rating").rating();

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    var active_tab = $("#trip-tab>div.active");
    var tab_index = $(active_tab).attr('data-index');

    if(typeof(Storage) !== "undefined") {
      sessionStorage.setItem('tab_index', tab_index);
    }
  });

  var submit_btn_text = "";
  if(typeof(Storage) !== "undefined") {
    if (sessionStorage.getItem('save-and-continue')) {
      var cont = sessionStorage.getItem('save-and-continue');
      if (cont) {
        $('#tripTab li:nth-child(2) a').tab("show");
        sessionStorage.removeItem('save-and-continue');
      }
    }

    if (sessionStorage.getItem('tab_index')) {
      var tab_index = sessionStorage.getItem('tab_index');
      $('#tripTab li:nth-child('+tab_index+') a').tab("show");
      sessionStorage.removeItem('tab_index');
    }
  }

  // save and continue button.
  $(document).on('click', '#form-continue', function(e) {
    e.preventDefault();
    var active_tab = $("#trip-tab>div.active");
    var tab_index = $(active_tab).attr('data-index');
    $('#tripTab li:nth-child('+ (parseInt(tab_index) + 1) +') a').tab("show");
    handleTripEditForm($("#add-trip-form-page"), true);
  });

  function initItinerarySortable() {
    $("#itinerary-block").sortable({
      handle: '.move-itinerary',
      cursor: 'move',
      scrollSpeed: 40,
      placeholder: "sortable-placeholder",
      start: function() {
            Toast.fire({
              type: 'info',
              position: 'center',
              timer: 2000,
              title: 'Drag up or down to re-arrange the itinerary.'
            });
      },
      update: function () {
        // rearrangeDay();
      }
    });
  }

  $(document).on('click', '.remove-itinerary', function(e) {
    // check if the div is greater than 1.
    if (parseInt($("#itinerary-block>.form-group").length) > 1) {
      var div = $(this).closest('.itinerary-group').remove();
      // reorder day.
      // rearrangeDay();
    }
  });

  function rearrangeDay() {
    $.each($("#itinerary-block>.form-group"), function(i, v) {
      $(v).find('.day-number').text(i + 1);
    });
  }

  $(document).on('click', '#add-itinerary-btn', function(e) {
    e.preventDefault();
    var n = parseInt($("#itinerary-block>.form-group").length) + 1;

    var div = '\
    <div class="form-group itinerary-group">\
      <div class="kt-timeline-v2 travel-timeline">\
          <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">\
              <div class="kt-timeline-v2__item">\
                  <span class="kt-timeline-v2__item-time">Day <span class="day-number"><input type="number" required class="form-control form-control-sm" style="width: 83px;"></span></span>\
                  <div class="kt-timeline-v2__item-cricle">\
                      <i class="fa fa-genderless kt-font-success"></i>\
                  </div>\
                  <div class="kt-timeline-v2__item-text kt-timeline-v2__item-text--bold">\
                    <div class="itinerary-block-action">\
                      <div>\
                        <button type="button" title="remove" class="btn btn-outline-danger btn-sm btn-elevate-hover btn-icon pull-right remove-itinerary"><i class="fa fa-times"></i></button>\
                          <div title="move" class="btn btn-outline-brand btn-sm btn-elevate-hover btn-icon pull-right move-itinerary mr-1"><i class="la la-unsorted"></i></div>\
                      </div>\
                    </div>\
                    <input type="text" name="trip_itineraries[][name]" id="input-trip-name" class="form-control mb-3 form-control-sm" placeholder="Title">\
                    <div class="itinerary-description-block">\
                      <div id="summernote-itinerary-'+n+'" class="summernote"></div>\
                    </div>\
                  </div>\
              </div>\
          </div>\
      </div>\
    </div>\
    ';

    $("#itinerary-block").append(div);
    $('#summernote-itinerary-' + n).summernote({
      height: 200
    });
    $("#itinerary-block").attr('data-n', n);
    initItinerarySortable();
  });

  function initSummerNote() {
    // $('#summernote-description').summernote({
    //   height: 400
    // });
    $('#summernote-accomodation').summernote();
    $('#summernote-meals').summernote();
    $('#summernote-transportation').summernote();
    $('#summernote-overview').summernote();
    $('#summernote-highlights').summernote();
    $('#summernote-important-note').summernote();

    $('#summernote-include').summernote();
    $('#summernote-exclude').summernote();
    $('#summernote-complimentary').summernote();

    $('#summernote-leader').summernote();
  }

  initSummerNote();

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
        max_altitude: {
          number: true
        },
        cost: {
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
      },
      "trip_seo[og_image]": {
          extension: "Only image files is allowed."
      }
    };

    $("#og_file").on('change', function(e) {
      var fileName = e.target.files[0].name;
      $("#og_file_name").html(fileName);
    });

    $("#map_file").on('change', function(e) {
      var fileName = e.target.files[0].name;
      $("#has_map_file").val(1);
      $("#map_file_name").html(fileName);
    });

    $("#pdf_file").on('change', function(e) {
      var fileName = e.target.files[0].name;
      $("#has_pdf_file").val(1);
      $("#pdf_file_name").html(fileName);
    });

    $("#remove-map-file-btn").on('click', function(event) {
      event.preventDefault();
      $("#has_map_file").val(0);
      $("#map_file").val('');
      $("#map_file_name").html("choose file");
      validator.resetForm();
    });

    $("#remove-pdf-file-btn").on('click', function(event) {
      event.preventDefault();
      $("#has_pdf_file").val(0);
      $("#pdf_file").val('');
      $("#pdf_file_name").html("choose file");
      validator.resetForm();
    });

		var validator = $("#edit-trip-form").validate({
      ignore: "",
			submitHandler: function(form, event) {
        event.preventDefault();
        submit_btn_text = $(form).find('button[type=submit]').html();
        var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Updating...');
        handleTripEditForm(form);
		  },
      rules: validation_rules,
      messages: validation_messages
		});
		var cropped = false;
    const image = document.getElementById('cropper-image');
    var cropper = "";

    $("#edit-trip-info-form").on('submit', function(e) {

      e.preventDefault();

      var form = $(this);
      submit_btn_text = $(form).find('button[type=submit]').html();
      var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Updating...');
      var formData = new FormData(form[0]);
      var trip_info = "";

      trip_info = {
        'accomodation' : $('#summernote-accomodation').summernote('code'),
        'trip_route' : $('#trip-route').val(),
        'meals' : $('#summernote-meals').summernote('code'),
        'transportation' : $('#summernote-transportation').summernote('code'),
        'overview' : $('#summernote-overview').summernote('code'),
        'highlights' : $('#summernote-highlights').summernote('code'),
        'important_note' : $('#summernote-important-note').summernote('code'),
        'best_season' : $('#best-season-text').val()
      };

      formData.append('trip_info', JSON.stringify(trip_info));

      $.ajax({
          url: "{{ route('admin.trips.info.update') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
            if (res.status === 1) {
              Toast.fire({
                type: 'success',
                position: 'center',
                title: 'Trip updated.'
              });
              scrollTop();
            }
          },
          complete: function() {
            $(form).find('button[type=submit]').attr('disabled', false).html(submit_btn_text);
          }
      });
    });

    $("#edit-trip-includes-form").on('submit', function(e) {

      e.preventDefault();

      var form = $(this);
      submit_btn_text = $(form).find('button[type=submit]').html();
      var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Updating...');
      var formData = new FormData(form[0]);
      var trip_include = "";

      trip_include = {
        'include' : $('#summernote-include').summernote('code'),
        'exclude' : $('#summernote-exclude').summernote('code'),
        'complimentary' : $('#summernote-complimentary').summernote('code'),
      };

      formData.append('trip_include_exclude', JSON.stringify(trip_include));

      $.ajax({
          url: "{{ route('admin.trips.includes.update') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
            if (res.status === 1) {
                Toast.fire({
                  type: 'success',
                  position: 'center',
                  title: 'Trip updated.'
                });
                scrollTop();
            }
          },
          complete: function() {
            $(form).find('button[type=submit]').attr('disabled', false).html(submit_btn_text);
          }
      });
    });

    $("#edit-trip-meta-form").on('submit', function(e) {

      e.preventDefault();

      var form = $(this);
      submit_btn_text = $(form).find('button[type=submit]').html();
      var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Updating...');
      var formData = new FormData(form[0]);
      var trip_leader = $('#summernote-leader').summernote('code');
      formData.append('trip_seo[about_leader]', trip_leader);

      $.ajax({
          url: "{{ route('admin.trips.meta.update') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
            if (res.status === 1) {
                Toast.fire({
                  type: 'success',
                  position: 'center',
                  title: 'Trip updated.'
                });
                scrollTop();
            }
          },
          complete: function() {
            $(form).find('button[type=submit]').attr('disabled', false).html(submit_btn_text);
          }
      });
    });

    $("#edit-trip-itinerary-form").on('submit', function(e) {
      e.preventDefault();

      var form = $(this);
      submit_btn_text = $(form).find('button[type=submit]').html();
      var btn = $(form).find('button[type=submit]').attr('disabled', true).html('Updating...');
      var formData = new FormData(form[0]);

      $.each($("#itinerary-block>.itinerary-group"), function(i, v) {
        var desc = $(v).find('.summernote').summernote('code');
        var day = $(v).find('.day-number').find('input').val();
        formData.append('trip_itineraries['+i+'][day]', day);
        formData.append('trip_itineraries['+i+'][display_order]', i + 1);
        formData.append('trip_itineraries['+i+'][description]', desc);
      });

      $.ajax({
          url: "{{ route('admin.trips.itineraries.update') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
            if (res.status === 1) {
                Toast.fire({
                  type: 'success',
                  position: 'center',
                  title: 'Trip updated.'
                });

                scrollTop();
            }
          },
          complete: function() {
            $(form).find('button[type=submit]').attr('disabled', false).html(submit_btn_text);
          }
      });
    });

    function scrollTop() {
      window.scrollTo(0, 0);
    }

    function handleTripEditForm(form) {
      var form = $(form);
      var formData = new FormData(form[0]);

      $.ajax({
          url: "{{ route('admin.trips.update') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
            if (res.status === 1) {
              Toast.fire({
                type: 'success',
                position: 'center',
                title: 'Trip updated.'
              });
              scrollTop();
                // location.href = '{{ route('admin.trips.index') }}';
            }
          },
          complete: function() {
            $(form).find('button[type=submit]').attr('disabled', false).html(submit_btn_text);
          }
      });
    }

    function readURL(input) {
      if (input.files && input.files[0]) {

        var pattern = /image-*/;

        if (!input.files[0].type.match(pattern)) {
          // toastr.warning('Only Images files are allowed');
          // input.value = '';
          // return;
        } else {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#cropper-image').attr('src', e.target.result);
            initCropperjs();
          }

          reader.readAsDataURL(input.files[0]);
        }

      }
    }

    // handle trip gallery add form
    $("#add-image").validate({
      ignore: "",
      submitHandler: function(form, event) {
        event.preventDefault();
        handleTripImageImageForm(form);
      },
      rules: {
        file: {
          required: true,
          extension: "jpeg|jpg|png|gif"
        }
      },
      messages: {
        file: {
          extension: "Only image files is allowed."
        }
      }
    });

    function handleTripImageImageForm(form) {
      var form = $(form);
      $(form).find('button[type=submit]').attr('disabled', true).html('Uploading...');
      var formData = new FormData(form[0]);
      if (cropper) {
        formData.append('cropped_data', JSON.stringify(cropper.getData()));
      }

      $.ajax({
          url: "{{ route('admin.trips.galleries.store') }}",
          type: 'POST',
          data: formData,
          dataType: 'json',
          processData: false,
          contentType: false,
          async: false,
          success: function(res) {
              if (res.status === 1) {
                toastr.success(res.message);
                loadGalleries();
                resetAddImageForm();
              }
          }
      });
    }

    $('#kt_modal_4').on('hidden.bs.modal', function () {
      resetAddImageForm();
    })

    function resetAddImageForm() {
      $('#kt_modal_4').modal('hide');
      $("#add-image")[0].reset();
      $("#add-image").find('button[type=submit]').attr('disabled', false).html('Upload');
      $('#cropper-image').attr('src', '{{ asset("img/default.gif") }}');
      if (cropped) {
        cropper.destroy();
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
	        aspectRatio: 3 / 2,
	        zoomable: false,
	        viewMode: 2,
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

    function loadGalleries() {
      var trip_id = "{{ $trip->id }}";
      $.ajax({
          url: "{{ url('/') }}" + '/admin/trips/'+ trip_id +'/galleries',
          type: 'GET',
          async: false,
          success: function(res) {
            var tbody = $("#gallery-table>tbody");

            if (res.data != "") {
              var tr = "";
              $.each(res.data, function(i, v) {
                console.log(v);
                tr += '\
                  <tr>\
                      <th scope="row">'+(i + 1)+'</th>\
                      <td><img style="tbl-img" src="'+v.thumbImageUrl+'"></td>\
                      <td>'+((v.caption)? v.caption: "--")+'</td>\
                      <td>'+((v.alt_tag)? v.alt_tag: "--")+'</td>\
                      <td>\
                        <button class="btn btn-sm btn-clean btn-icon btn-icon-md kt_sweetalert_delete_image" data-id="'+v.id+'" title="Delete">\
                          <i class="la la-trash"></i>\
                        </button>\
                      </td>\
                  </tr>\
                ';
              });
              tbody.html(tr);
            } else {
              var tr = '<tr><td colspan="5">No galleries.</td></tr>';
              tbody.html(tr);
            }
          }
      });
    }

    $(document).on('click', '.kt_sweetalert_delete_image', function(event) {
      var e = $(this);

      swal.fire({
          title: 'Are you sure you want to delete this?',
          // text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes'
      }).then(function(result) {
          if (result.value) {
            var id = e.attr('data-id');
            var action_url = '{{ url('') }}' + '/admin/trip/gallery/delete/' + id;
            $.ajax({
              url: action_url,
              type: "DELETE",
              dataType: "json",
              async: "false",
              success: function(res) {
                loadGalleries();
                Toast.fire({
                  type: 'success',
                  title: 'The image has been deleted.'
                })
              }
            })
          }
      });
    });

    initItinerarySortable();
});

</script>
@endpush
