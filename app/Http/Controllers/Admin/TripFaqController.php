<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\TripFaq;
use Illuminate\Support\Facades\Log;

class TripFaqController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    return view('admin.tripFaqs.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$trips = \App\Trip::all();
	    return view('admin.tripFaqs.add', compact('trips'));
	}

	public function faqList($tripId)
	{
	    $faq_list = TripFaq::where('trip_id', '=', $tripId)->get();
	    return response()->json([
	        'data' => $faq_list
	    ]);
	}

	public function faqs($tripId)
	{
		$trip = \App\Trip::find($tripId);
		return view('admin.tripFaqs.faqs-list', compact('trip'));
	}

	public function tripList()
	{
	    $trips = \App\Trip::whereHas('trip_faqs')->get();
	    return response()->json([
	        'data' => $trips
	    ]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
	    $status = 0;
	    $msg = "";
	    $trip_faq = new TripFaq;
	    $trip_faq->trip_id = $request->trip_id;
	    $trip_faq->title = $request->title;
	    $trip_faq->description = $request->description;

	    if ($trip_faq->save()) {
	        $status = 1;
	        $msg = "Trip faq created successfully.";
	        session()->flash('message', $msg);
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
	    $trip_faq = TripFaq::find($id);

	    $trips = \App\Trip::all();
	    return view('admin.tripFaqs.edit', compact('trip_faq', 'trips'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request)
	{
	    $status = 0;
	    $msg = "";
	    $trip_faq = TripFaq::find($request->id);
	    $trip_faq->trip_id = $request->trip_id;
	    $trip_faq->title = $request->title;
	    $trip_faq->description = $request->description;

	    if ($trip_faq->save()) {
	        $status = 1;
	        $msg = "Trip faq updated successfully.";
	        session()->flash('message', $msg);
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ]);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
	    $status = 0;
	    $http_status_code = 400;
	    $msg = "";
	    $path = 'public/trip-faqs/';

	    if (TripFaq::find($id)->delete()) {
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "Trip faq has been deleted";
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ], $http_status_code);
	}

	public function destroyAllTripFaqs($tripId)
	{
		$status = 0;
		$http_status_code = 404;
		$msg = "";

		if (TripFaq::where('trip_id', '=', $tripId)->delete()) {
		    $status = 1;
		    $http_status_code = 200;
		    $msg = "All trip faqs has been deleted";
		}

		return response()->json([
		    'status' => $status,
		    'message' => $msg
		], $http_status_code);
	}

	public function publish($id)
	{
		$success = false;
		$message = "";

		$trip_faq = TripFaq::find($id);

		if ($trip_faq) {
		    if ($trip_faq->status == 1) {
		        $trip_faq->status = 0;
		    } else {
		        $trip_faq->status = 1;
		    }

		    if ($trip_faq->save()) {
		        $message = "Review has been published.";
		        $success = true;
		    }

		} else {
		    $message = __('alerts.not_found_error');
		}

		return response()->json([
		    'data' => [],
		    'success' => $success,
		    'message' => $message
		]);
	}
}
