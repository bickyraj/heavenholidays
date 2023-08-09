<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Destination;

class DestinationController extends Controller
{
	public function show($slug)
	{
        $destination = Destination::where('slug', '=', $slug)->first();
		$seo = $destination->seo;
		$destinations = \App\Destination::select('id', 'name')->get();
		$activities = \App\Activity::select('id', 'name')->get();
		return view('front.destinations.show', compact('destination', 'destinations', 'activities', 'seo'));
	}
}
