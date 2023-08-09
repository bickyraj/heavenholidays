<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Banner;

class BannerController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $banners = Banner::get()->toArray();
	    return view('admin.banners.index', compact('banners'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
	    return view('admin.banners.add');
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
	    $banner = new Banner;
	    $banner->status = 1;
	    $banner->caption = $request->caption;
	    $banner->image_alt = $request->image_alt;
	    $banner->btn_link = $request->btn_link;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $banner->image_name = $imageName;
	    }

	    if ($banner->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $image_quality = 100;

	            if (($banner->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/banners/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $banner['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

	            // thumbnail image
	            $image->fit(300, 100, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $banner['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
	            $status = 1;
	        }
	        $status = 1;
	        $msg = "Banner created successfully.";
            session()->flash('message', $msg);
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ]);
	}

	public function bannerList()
	{
	    $banners = Banner::all();
	    return response()->json([
	        'data' => $banners
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
	    $banner = Banner::find($id);
	    return view('admin.banners.edit', compact('banner'));
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
	    $banner = Banner::find($request->id);
	    $banner->image_alt = $request->image_alt;
	    $banner->caption = $request->caption;
	    $banner->btn_link = $request->btn_link;
	    $banner->status = 1;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
	        $imageName = $imageNameUniqid;

	        $banner->image_name = $imageName;
	    }

	    if ($banner->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $path = 'public/banners/';
	            Storage::deleteDirectory($path . $banner->id);

	            $image_quality = 100;

	            if (($banner->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $cropped_data = json_decode($request->cropped_data, true);
	            $path = 'public/banners/';

	            $image = Image::make($request->file);

	            // crop image
	            $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

	            Storage::put($path . $banner['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

	            // thumbnail image
	            $image->fit(300, 100, function ($constraint) {
	                $constraint->aspectRatio();
	            });

	            Storage::put($path . $banner['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
	            $status = 1;
	        } else {
                if (isset($request->cropped_data) && !empty($request->cropped_data)) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/banners/';
                    $image = Image::make(Storage::get('public/banners/' . $banner->id . '/' . $banner->image_name));

                    Storage::deleteDirectory($path . $banner->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($banner->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($banner->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $banner['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(300, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $banner['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $banner->image_name = $imageNameUniqid;
                    $banner->save();
                }
            }

	        $status = 1;
	        $msg = "Banner updated successfully.";
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
	    $path = 'public/banners/';

	    if (Banner::find($id)->delete()) {
	        Storage::deleteDirectory($path . $id);
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "Banner has been deleted";
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ], $http_status_code);
	}
}
