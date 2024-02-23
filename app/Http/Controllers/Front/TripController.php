<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Helpers\Setting;
use App\Services\Recaptcha\RecaptchaService;
use App\Trip;
use Carbon\Carbon;
use DB;

class TripController extends Controller
{
    private $page_limit = 6;

    public function show($slug)
    {
        $trip = Trip::where('slug', '=', $slug)->with([
            'trip_galleries',
            'trip_sliders',
            'trip_info',
            'trip_include_exclude',
            'trip_itineraries' => function ($q) {
                $q->orderBy('day', 'asc');
            },
           'trip_reviews',
            'similar_trips',
            'addon_trips',
            'trip_faqs',
            'trip_seo',
            'trip_departures' => function ($q) {
                $q->where([
                    ['status', 1],
                    ['from_date', '>=', Carbon::today()]
                ])->orderBy('from_date', 'ASC')->take(15);
            }
        ])->first();

        $canMakeChart = true;
        $elevations = $trip->trip_itineraries->map(function ($day) use (&$canMakeChart) {
            if (!is_numeric($day->max_altitude)) {
                $canMakeChart = false;
            }
            return [
                'place_name' => $day->place_name ?? $day->day,
                'max_altitude' => $day->max_altitude,
            ];
        })->toArray();

        $why_choose_us = \App\WhyChoose::select('id', 'title')->latest()->get();
        $blogs = \App\Blog::select('id', 'name', 'slug')->latest()->limit(5)->get();
        if ($trip->people_price_range != null && count($trip->people_price_range) > 0) {
            $isGroupDiscountsShown = true;
        } else {
            $isGroupDiscountsShown = false;
        }
        return view('front.trips.show', compact('trip', 'blogs', 'canMakeChart', 'elevations', 'why_choose_us', 'isGroupDiscountsShown'));
    }

    public function booking($slug)
    {
        $trip = Trip::where('slug', '=', $slug)->first();
        $price_ranges = $trip->people_price_range;

        return view('front.trips.booking', compact('trip', 'price_ranges'));
    }

    public function departureBooking($slug, $departureId)
    {
        $trip = Trip::where('slug', '=', $slug)->first();
        $trip_departure = \App\TripDeparture::find($departureId);

        return view('front.trips.departure-booking', compact('trip', 'trip_departure'));
    }

    public function privateDepartureBooking(Request $request, $slug)
    {
        $trip = Trip::where('slug', '=', $slug)->first();
        $departure_date = $request->date;
        return view('front.trips.departure-booking', compact('trip', 'departure_date'));
    }

    public function search(Request $request)
    {
        $keyword = $request->keyword;
        $destination_ids = $request->dest;
        $activity_ids = $request->act;
        $sortBy = $request->price;

        $query = Trip::query();

        if (isset($keyword) && !empty($keyword)) {
            $query->where([
                ['name', 'LIKE', "%" . $keyword . "%"]
            ]);
        } else {
            if ($destination_ids) {
                $destination_ids = explode(',', $request->dest);
                $query->whereHas('destination', function ($q) use ($destination_ids) {
                    $q->whereIn('destinations.id', $destination_ids);
                });
            }

            if ($activity_ids) {
                $activity_ids = explode(',', $request->act);
                $query->whereHas('activities', function ($q) use ($activity_ids) {
                    $q->whereIn('activities.id', $activity_ids);
                });
            }

            if ($sortBy) {
                if ($sortBy == "price_l_h") {
                    $query->orderBy('offer_price', 'ASC');
                } else {
                    $query->orderBy('offer_price', 'DESC');
                }
            }
        }

        $trips = $query->latest()->get();

        $destinations = \App\Destination::where('status', '=', 1)->get();
        $activities = \App\Activity::where('status', '=', 1)->get();

        return view('front.trips.search', compact('destinations', 'activities', 'trips'));
    }

    public function searchAjax(Request $request)
    {
        $success = false;
        $message = "";
        $keyword = $request->keyword;
        $query = Trip::query();

        if (isset($keyword) && !empty($keyword)) {
            $query->where([
                ['name', 'LIKE', "%" . $keyword . "%"]
            ]);
        }

        $trips = $query->select('name', 'slug')->orderBy('name', 'asc')->get();
        if ($trips) {
            $success = true;
            $message = "List fetched successfully.";
        }
        return response()->json([
            'data' => $trips,
            'success' => $success,
            'message' => $message
        ]);
    }

    public function filter(Request $request)
    {
        $keyword = $request->keyword;
        $destination_id = $request->destination_id;
        $activity_id = $request->activity_id;
        $sortBy = $request->sortBy;
        $duration = explode(",", $request->duration);
        $price = explode(",", $request->price);
        $region_id = $request->region_id;
        $query = Trip::query();

        if (isset($keyword) && !empty($keyword)) {
            $query->where([
                ['name', 'LIKE', "%" . $keyword . "%"]
            ]);
        }

        if (isset($duration) && !empty($duration)) {
            $query->whereRaw('CAST(duration AS UNSIGNED) BETWEEN ? AND ?', [$duration[0], $duration[1]]);
        }

        if (isset($price) && !empty($price)) {
            // $query->whereBetween('cost', [$price[0], $price[1]]);
            $minValue = $price[0];
            $maxValue = $price[1];

            $query->where(function ($query) use ($minValue, $maxValue) {
                $query->where(function ($query) use ($minValue, $maxValue) {
                    $query->whereRaw('IF(offer_price IS NULL OR offer_price = 0, cost, offer_price) BETWEEN ? AND ?', [$minValue, $maxValue]);
                })
                    ->orWhere(function ($query) use ($minValue) {
                        if ($minValue == 0) {
                            $query->whereNull('cost')
                                ->whereNull('offer_price');
                        }
                    });
            });
        }

        if (isset($region_id) && !empty($region_id)) {
            $query->whereHas('region', function ($q) use ($region_id) {
                $q->where('regions.id', '=', $region_id);
            });
        }

        if (isset($destination_id) && !empty($destination_id)) {
            $query->whereHas('destination', function ($q) use ($destination_id) {
                $q->where('destinations.id', '=', $destination_id);
            });
        }

        if (isset($activity_id) && !empty($activity_id)) {
            $query->whereHas('activities', function ($q) use ($activity_id) {
                $q->where('activities.id', '=', $activity_id);
            });
        }

        if (isset($sortBy) && !empty($sortBy)) {
            $query->orderBy('offer_price', $sortBy);
        }

        $trips = $query->latest()->paginate($this->page_limit);
        $html = "";
        if (!empty($trips)) {
            foreach ($trips as $trip) {
                $html .= view('front.elements.tour-card')->with(['tour' => $trip])->render();
            }
        }

        return response()->json([
            'data' => $html,
            'pagination' => [
                'current_page' => $trips->currentPage(),
                'next_page' => $trips->nextPageUrl() ? true : false,
                'total' => $trips->total()
            ],
            'success' => true,
            'message' => 'List fetched'
        ]);
    }

    public function list()
    {
        $destinations = \App\Destination::where('status', '=', 1)->get();
        $activities = \App\Activity::where('status', '=', 1)->get();
        $trips = Trip::where('status', 1)->get();
        return view('front.trips.listing', compact('destinations', 'activities', 'trips'));
    }

    public function bookingStore(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $trip = Trip::find($request->id);

        $request->merge([
            'trip_name' => $trip->name,
            'ip_address' => $request->ip()
        ]);

        try {
            Mail::send('emails.common', ['body' => $request], function ($message) use ($request) {
                $message->to(Setting::get('email'));
                $message->from($request->email);
                $message->subject('Trip Booking');
            });
            session()->flash('success_message', "Thank you for your Booking. We'll contact you very soon.");
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            return redirect()->back();
        }
    }

    public function departureBookingStore(Request $request)
    {
        $request->validate([
            'departure_id' => 'required'
        ]);

        $verifiedRecaptcha = RecaptchaService::verifyRecaptcha($request->get('google_recaptcha'));

        if (!$verifiedRecaptcha) {
            session()->flash('error_message', 'Google recaptcha error.');
            return redirect()->back();
        }

        $trip = Trip::select('id', 'name', 'slug')->find($request->id);
        $departure = \App\TripDeparture::find($request->departure_id);

        $request->merge([
            'trip_name' => $trip->name,
            'from_date' => $departure->from_date,
            'to_date' => $departure->to_date,
            'status_info' => $departure->statusInfo,
            'ip_address' => $request->ip()
        ]);

        try {
            Mail::send('emails.departure-booking', ['body' => $request], function ($message) use ($request) {
                $message->to(Setting::get('email'));
                $message->from($request->email);
                $message->subject('Trip Booking');
            });
            session()->flash('success_message', "Thank you for your Booking. We'll contact you very soon.");
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
        return redirect()->route('front.trips.departure-booking', [
            'slug' => $trip->slug,
            'id' => $departure->id
        ]);
    }

    public function customizeStore(Request $request)
    {
        $trip = Trip::find($request->id);
        $request->merge([
            'trip' => $trip,
            'ip_address' => $request->ip()
        ]);

        try {
            Mail::send('emails.customize-trip', ['body' => $request], function ($message) use ($request) {
                $message->to(Setting::get('email'));
                $message->from($request->email);
                $message->subject('Customized Trip');
            });
            // return redirect()->route('front.trips.booking', ['slug' => $trip->slug]);
            session()->flash('success_message', "Thank you for your enquiry. We'll contact you very soon.");
            return redirect()->back();
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            session()->flash('error_message', __('alerts.save_error'));
            return redirect()->back();
        }
    }

    public function customize($slug)
    {
        $trip = Trip::where('slug', '=', $slug)->first();

        return view('front.trips.customize-trip', compact('trip'));
    }

    public function allTripGallery()
    {
        $trips = Trip::all();
        return view('front.galleries.index', compact('trips'));
    }

    public function gallery($slug)
    {
        $trip = Trip::where('slug', '=', $slug)->with('trip_galleries')->first();
        return view('front.trips.gallery', compact('trip'));
    }

    public function print($slug)
    {
        $trip = Trip::where('slug', '=', $slug)->with('trip_include_exclude', 'trip_itineraries', 'trip_info')->first();
        return view('front.trips.print', compact('trip'));
    }
}
