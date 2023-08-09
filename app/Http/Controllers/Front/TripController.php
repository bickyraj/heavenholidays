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

class TripController extends Controller
{
	public function show($slug)
	{
		$trip = Trip::where('slug', '=', $slug)->with([
			'trip_galleries',
			'trip_info',
			'trip_include_exclude',
			'trip_itineraries' => function($q) {
                $q->orderBy('day', 'asc');
            },
			'trip_reviews',
			'similar_trips',
			'addon_trips',
			'trip_faqs',
			'trip_seo',
			'trip_departures' => function($q) {
				$q->where([
                    ['status', 1],
                    ['from_date', '>=', Carbon::today()]
                ])->limit(10)->orderBy('from_date', 'ASC');
			}
        ])->first();

		$why_choose_us = \App\WhyChoose::select('id', 'title')->latest()->get();
		$blogs = \App\Blog::select('id', 'name', 'slug')->latest()->limit(5)->get();
		return view('front.trips.show', compact('trip', 'blogs', 'why_choose_us'));
	
	
	}

	public function booking($slug)
	{
		$trip = Trip::where('slug', '=', $slug)->first();

		return view('front.trips.booking', compact('trip'));
	}

	public function departureBooking($slug, $departureId)
	{
		$trip = Trip::where('slug', '=', $slug)->first();
		$trip_departure = \App\TripDeparture::find($departureId);

		return view('front.trips.departure-booking', compact('trip', 'trip_departure'));
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
				$query->whereHas('destination', function($q) use ($destination_ids) {
					$q->whereIn('destinations.id', $destination_ids);
				});
			}

			if ($activity_ids) {
				$activity_ids = explode(',', $request->act);
				$query->whereHas('activities', function($q) use ($activity_ids) {
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
        $region_id = $request->region;
		$query = Trip::query();

		if (isset($keyword) && !empty($keyword)) {
			$query->where([
				['name', 'LIKE', "%" . $keyword . "%"]
			]);
		} else {

            if (!empty($region_id)) {
                $query->whereHas('region', function($q) use ($region_id) {
                    $q->where('regions.id', '=', $region_id);
                });
            }

			if ($destination_id) {
				$query->whereHas('destination', function($q) use ($destination_id) {
					$q->where('destinations.id', '=', $destination_id);
				});
			}

			if ($activity_id) {
				$query->whereHas('activities', function($q) use ($activity_id) {
					$q->where('activities.id', '=', $activity_id);
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
		$html = view('front.elements.trip-block')->with(compact('trips'))->render();

		return response()->json([
			'data' => $html,
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
        try {
            $trip = Trip::find($request->id);
            $request->merge([
                'trip_name' => $trip->name,
                'ip_address' => $request->ip()
            ]);

			Mail::send('emails.common', ['body' => $request], function ($message) use ($request) {
			    $message->to(Setting::get('email'));
			    $message->from($request->email);
			    $message->subject('Trip Booking');
			});
			session()->flash('success_message', "Thank you for your Booking. We'll contact you very soon.");
            session()->flash('trip_name', $trip->name);
            return redirect()->back();
		//	return redirect()->route('front.payment');
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
		public function departure()
	{
		$data['departures'] = TripDeparture::where([['status', 1],['from_date', '>=', Carbon::today()]])->orderBy('from_date', 'asc')->limit(10)->get();
	}
}