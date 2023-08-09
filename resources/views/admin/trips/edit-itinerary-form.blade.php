@if(iterator_count($trip->trip_itineraries))
@foreach($trip->trip_itineraries as $itinerary)
<div class="form-group itinerary-group">
  <div class="kt-timeline-v2 travel-timeline">
      <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
          <div class="kt-timeline-v2__item">
              <span class="kt-timeline-v2__item-time">Day <span class="day-number"><input type="number" required value="{{ $itinerary->day }}" class="form-control form-control-sm" style="width: 83px;"></span></span>
              <div class="kt-timeline-v2__item-cricle">
                  <i class="fa fa-genderless kt-font-success"></i>
              </div>
              <div class="kt-timeline-v2__item-text kt-timeline-v2__item-text--bold">
                <div class="itinerary-block-action">
                  <div>
                    <button type="button" title="remove" class="btn btn-outline-danger btn-sm btn-elevate-hover btn-icon pull-right remove-itinerary"><i class="fa fa-times"></i></button>
                    <div title="move" class="btn btn-outline-brand btn-sm btn-elevate-hover btn-icon pull-right move-itinerary mr-1"><i class="la la-unsorted"></i></div>
                  </div>
                </div>
                <input type="text" name="trip_itineraries[][name]" id="input-trip-name" value="{{ $itinerary->name }}" class="form-control mb-3 form-control-sm" placeholder="Title">
                <div class="itinerary-description-block">
                  <div id="summernote-itinerary-{{ $itinerary->day }}" class="summernote"><?= $itinerary->description; ?></div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endforeach
@else
<div class="form-group itinerary-group">
  <div class="kt-timeline-v2 travel-timeline">
      <div class="kt-timeline-v2__items  kt-padding-top-25 kt-padding-bottom-30">
          <div class="kt-timeline-v2__item">
              <span class="kt-timeline-v2__item-time">Day <span class="day-number"><input type="number" required class="form-control form-control-sm" style="width: 83px;"></span></span>
              <div class="kt-timeline-v2__item-cricle">
                  <i class="fa fa-genderless kt-font-success"></i>
              </div>
              <div class="kt-timeline-v2__item-text kt-timeline-v2__item-text--bold">
                <div class="itinerary-block-action">
                  <div>
                    <button type="button" title="remove" class="btn btn-outline-danger btn-sm btn-elevate-hover btn-icon pull-right remove-itinerary"><i class="fa fa-times"></i></button>
                    <div title="move" class="btn btn-outline-brand btn-sm btn-elevate-hover btn-icon pull-right move-itinerary mr-1"><i class="la la-unsorted"></i></div>
                  </div>
                </div>
                <input type="text" name="trip_itineraries[][name]" id="input-trip-name" class="form-control mb-3 form-control-sm" placeholder="Title">
                <div class="itinerary-description-block">
                  <div id="summernote-itinerary-1" class="summernote"></div>
                </div>
              </div>
          </div>
      </div>
  </div>
</div>
@endif