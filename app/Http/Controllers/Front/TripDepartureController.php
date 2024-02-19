<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\TripDeparture;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripDepartureController extends Controller
{
    public function filter(Request $request, $month = null)
    {
        if (isset($request->month)) {
            $data['departures'] = TripDeparture::where([
                ['status', 1],
                ['from_date', '>=', Carbon::today()]
            ])->whereMonth('from_date', $request->month)->orderBy('from_date', 'asc')->get();
        }
        $html = "";
        foreach ($data['departures'] as $departure) {
            $html .= view('front.elements.tour_departure_card', ['departure' => $departure])->render();
        }

        return response()->json([
            'data' => $html,
            'success' => true,
            'message' => 'List fetched.'
        ]);
    }
}
