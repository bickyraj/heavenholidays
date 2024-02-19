<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Trip;
use App\TripInfo;
use App\TripSeo;
use App\TripIncludeExclude;
use App\TripItinerary;
use App\TripGallery;
use App\TripSlider;
use Image;
use Illuminate\Support\Facades\Log;
use Mockery\Undefined;

class TripSliderController extends Controller
{
    public function storeTripGallery(Request $request)
    {
        $request->validate([
            'gallery' => "required|mimes:jpeg,jpg,png,gif|max:10000",
        ]);

        $status = 0;
        $msg = "";
        $gallery = new TripSlider;
        $gallery->status = 1;
        $gallery->trip_id = $request->trip_id;
        $gallery->alt_tag = $request->alt_tag;

        if ($request->hasFile('gallery')) {
            $imageName = $request->gallery->getClientOriginalName();
            $gallery->original_image_name = $imageName;
            $imageSize = $request->gallery->getClientSize();
            $imageType = $request->gallery->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $gallery->image_name = $imageName;
        }

        if ($gallery->save()) {
            // save image.
            if ($request->hasFile('gallery')) {

                $image_quality = 100;

                if (($gallery->image_size / 1000000) > 1) {
                    $image_quality = 75;
                }

                $path = 'public/trip-sliders/';

                $image = Image::make($request->gallery);

                Storage::put($path . $gallery['trip_id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }

            $status = 1;
            $msg = "Gallery saved successfully.";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ]);
    }

    public function deleteTripImage($id)
    {
        $status = 0;
        $http_status_code = 400;
        $msg = "";
        $path = 'public/trip-sliders/';

        $gallery = TripSlider::find($id);

        $trip_id = $gallery->trip_id;
        $image_name = $gallery->image_name;

        if ($gallery->delete()) {
            Storage::delete($path . $trip_id . '/' . $image_name);
            $status = 1;
            $http_status_code = 200;
            $msg = "Gallery has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function editSlider($id)
    {
        $slider = TripSlider::find($id);
        return view('admin.trips.edit-gallery', compact('slider'));
    }

    public function updateTripSlider(Request $request)
    {
        $status = 0;
        $msg = "";
        $slider = TripSlider::find($request->id);
        $slider->alt_tag = $request->alt_tag;
        $slider->status = 1;

        if ($request->hasFile('gallery')) {
            $imageName = $request->gallery->getClientOriginalName();
            $imageType = $request->gallery->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $slider->image_name = $imageName;
        }

        if ($slider->save()) {
            // save image.
            if ($request->hasFile('gallery')) {

                $path = 'public/trip-sliders/';
                // Storage::deleteDirectory($path . $slider->trip_id);

                $image_quality = 100;

                if (($slider->image_size / 1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/trip-sliders/';

                $image = Image::make($request->gallery);

                Storage::put($path . $slider['trip_id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }

            $status = 1;
            $msg = "Gallery updated successfully.";
            session()->flash('message', $msg);
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ]);
    }
}
