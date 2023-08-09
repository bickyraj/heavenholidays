<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Activity;

class ActivityController extends Controller
{
	public function show($slug)
	{
		$activity = Activity::where('slug', '=', $slug)->first();
		$seo = $activity->seo;
		$destinations = \App\Destination::select('id', 'name')->get();
		$activities = \App\Activity::select('id', 'name')->get();

		return view('front.activities.show', compact('activity', 'destinations', 'activities', 'seo'));
	}
}
