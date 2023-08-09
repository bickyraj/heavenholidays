<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Region;

class RegionController extends Controller
{
	public function show($slug)
	{
		$region = Region::where('slug', '=', $slug)->first();
		$seo = $region->seo;
		$destinations = \App\Destination::select('id', 'name')->get();
		$activities = \App\Activity::select('id', 'name')->get();
		return view('front.regions.show', compact('region', 'destinations', 'activities', 'seo'));
	}
}
