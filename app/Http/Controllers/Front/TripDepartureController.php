<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\TripDeparture;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripDepartureController extends Controller
{
    public function filter(Request $request)
    {
        // if (isset($request->month)) {
        //     $data['departures'] = TripDeparture::where([
        //         ['status', 1],
        //         ['from_date', '>=', Carbon::today()],
        //     ])->whereMonth('from_date', $request->month)->limit(10)->orderBy('from_date', 'asc')->get();
        // }
        // $html = "";
        // foreach ($data['departures'] as $departure) {
        //     $html .= view('front.elements.tour_departure_card', ['departure' => $departure])->render();
        // }
        
        $query = TripDeparture::query();
        if (isset($request->month)) {
            $query = $query->where([
                ['status', 1],
                ['from_date', '>=', Carbon::today()],
            ])->whereMonth('from_date', $request->month)->limit(100)->orderBy('from_date', 'asc');
        }

        if (isset($request->trip_id)) {
            $query = $query->where('trip_id', $request->trip_id);
        }
        $data['departures'] = $query->get();
        $html = "";
        if (isset($request->trip_id)) {
            foreach ($data['departures'] as $departure) {
                $html .= view('front.elements.tour_departure_card_new', ['departure' => $departure])->render();
            }
        } else {
            foreach ($data['departures'] as $departure) {
                $html .= view('front.elements.tour_departure_card', ['departure' => $departure])->render();
            }
        }

        return response()->json([
            'data' => $html,
            'success' => true,
            'message' => 'List fetched.'
        ]);
    }
}
