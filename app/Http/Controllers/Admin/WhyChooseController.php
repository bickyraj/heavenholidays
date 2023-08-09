<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\WhyChoose;
use Image;
use Illuminate\Support\Facades\Log;

class WhyChooseController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $chooses = WhyChoose::get()->toArray();
	    return view('admin.whyChooses.index', compact('chooses'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    return view('admin.whyChooses.add');
	}

	public function whyChooseList()
	{
	    $chooses = WhyChoose::all();
	    return response()->json([
	        'data' => $chooses
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
		$request->validate([
			'title' => 'required',
			'description' => 'required',
            'file' => 'nullable|image|mimes:png,jpg,jpeg,gif|max:20000'
		]);

	    $status = 0;
	    $msg = "";
	    $why_choose = new WhyChoose;
	    $why_choose->title = $request->title;
	    $why_choose->description = $request->description;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $why_choose->image_name = $imageName;
	    }

	    if ($why_choose->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $image_quality = 100;

	            if (($why_choose->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/why-chooses/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $why_choose['id'] . '/' . $imageName, (string) $image->encode($imageType, $image_quality));

	            // thumbnail image
	            $image->fit(200, 200, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $why_choose['id'] . '/thumb_' . $imageName, (string) $image->encode($imageType, $image_quality));
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
	    $why_choose = WhyChoose::find($id);

	    $trips = \App\Trip::all();
	    return view('admin.whyChooses.edit', compact('why_choose', 'trips'));
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
		$request->validate([
			'title' => 'required',
			'description' => 'required',
            'file' => 'nullable|image|mimes:png,jpg,jpeg|max:20000'
		]);
		
	    $status = 0;
	    $msg = "";
	    $why_choose = WhyChoose::find($request->id);
	    $why_choose->title = $request->title;
	    $why_choose->description = $request->description;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $why_choose->image_name = $imageName;
	    }

	    if ($why_choose->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $path = 'public/why-chooses/';
	            Storage::deleteDirectory($path . $why_choose->id);

	            $image_quality = 100;

	            if (($why_choose->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/why-chooses/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $why_choose['id'] . '/' . $imageName, (string) $image->encode($imageType, $image_quality));

	            // thumbnail image
	            $image->fit(200, 200, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $why_choose['id'] . '/thumb_' . $imageName, (string) $image->encode($imageType, $image_quality));
	            $status = 1;
	        } else {
	            if (isset($why_choose->image_name) && isset($request->cropped_data) && !empty($request->cropped_data)) {
	                $cropped_data = json_decode($request->cropped_data, true);

	                $path = 'public/why-chooses/';
	                $image = Image::make(Storage::get('public/why-chooses/' . $why_choose->id . '/' . $why_choose->image_name));

	                Storage::deleteDirectory($path . $why_choose->id);

	                // crop image
	                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	                $ext = pathinfo($why_choose->image_name, PATHINFO_EXTENSION);

	                $imageNameUniqid = md5($why_choose->image_name . microtime()) . '.' . $ext;

	                Storage::put($path . $why_choose['id'] . '/' . $imageNameUniqid, (string) $image->encode($ext, 100));

	                // thumbnail image
	                $image->fit(100, 100, function ($constraint) {
	                    $constraint->aspectRatio();
	                });

	                Storage::put($path . $why_choose['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode($ext, 100));

	                $why_choose->image_name = $imageNameUniqid;
	                $why_choose->save();
	            }
	        }

	        $status = 1;
	        $msg = "Updated successfully.";
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
	    $path = 'public/why-chooses/';

	    if (WhyChoose::find($id)->delete()) {
	        Storage::deleteDirectory($path . $id);
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "The option has been deleted";
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

		$why_choose = WhyChoose::find($id);

		if ($why_choose) {
		    if ($why_choose->status == 1) {
		        $why_choose->status = 0;
		    } else {
		        $why_choose->status = 1;
		    }

		    if ($why_choose->save()) {
		        $message = "The option has been published.";
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
