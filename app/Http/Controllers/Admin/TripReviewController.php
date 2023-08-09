<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\TripReview;
use Image;
use Illuminate\Support\Facades\Log;

class TripReviewController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $reviews = TripReview::get()->toArray();
	    return view('admin.tripReviews.index', compact('reviews'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		$trips = \App\Trip::all();
	    return view('admin.tripReviews.add', compact('trips'));
	}

	public function reviewsList()
	{
	    $reviews = TripReview::all();
	    return response()->json([
	        'data' => $reviews
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
	    $trip_review = new TripReview;
	    $trip_review->trip_id = $request->trip_id;
	    $trip_review->title = $request->title;
	    $trip_review->review_name = $request->review_name;
	    $trip_review->review = $request->review;
	    $trip_review->review_country = $request->review_country;
	    $trip_review->rating = $request->rating;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $trip_review->image_name = $imageName;
	        $trip_review->image_type = $imageType;
	        $trip_review->image_size = $imageSize;
	    }

	    if ($trip_review->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $image_quality = 100;

	            if (($trip_review->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/trip-reviews/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $trip_review['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

	            // thumbnail image
	            $image->fit(100, 100, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $trip_review['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
	            $status = 1;
	        }
	        $status = 1;
	        $msg = "Trip review created successfully.";
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
	    $trip_review = TripReview::find($id);

	    $trips = \App\Trip::all();
	    return view('admin.tripReviews.edit', compact('trip_review', 'trips'));
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
	    $trip_review = TripReview::find($request->id);
	    $trip_review->trip_id = $request->trip_id;
	    $trip_review->title = $request->title;
	    $trip_review->review_name = $request->review_name;
	    $trip_review->review = $request->review;
	    $trip_review->review_country = $request->review_country;
	    $trip_review->rating = $request->rating;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $trip_review->image_name = $imageName;
	        $trip_review->image_type = $imageType;
	        $trip_review->image_size = $imageSize;
	    }

	    if ($trip_review->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $path = 'public/trip-reviews/';
	            Storage::deleteDirectory($path . $trip_review->id);

	            $image_quality = 100;

	            if (($trip_review->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/trip-reviews/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $trip_review['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

	            // thumbnail image
	            $image->fit(100, 100, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $trip_review['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
	            $status = 1;
	        } else {
	            if (isset($trip_review->image_name) && isset($request->cropped_data) && !empty($request->cropped_data)) {
	                $cropped_data = json_decode($request->cropped_data, true);

	                $path = 'public/trip-reviews/';
	                $image = Image::make(Storage::get('public/trip-reviews/' . $trip_review->id . '/' . $trip_review->image_name));

	                Storage::deleteDirectory($path . $trip_review->id);

	                // crop image
	                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	                $ext = pathinfo($trip_review->image_name, PATHINFO_EXTENSION);

	                $imageNameUniqid = md5($trip_review->image_name . microtime()) . '.' . $ext;

	                Storage::put($path . $trip_review['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

	                // thumbnail image
	                $image->fit(100, 100, function ($constraint) {
	                    $constraint->aspectRatio();
	                });

	                Storage::put($path . $trip_review['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

	                $trip_review->image_name = $imageNameUniqid;
	                $trip_review->save();
	            }
	        }

	        $status = 1;
	        $msg = "Trip review updated successfully.";
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
	    $path = 'public/trip-reviews/';

	    if (TripReview::find($id)->delete()) {
	        Storage::deleteDirectory($path . $id);
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "Trip review has been deleted";
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

		$trip_review = TripReview::find($id);

		if ($trip_review) {
		    if ($trip_review->status == 1) {
		        $trip_review->status = 0;
		    } else {
		        $trip_review->status = 1;
		    }

		    if ($trip_review->save()) {
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
