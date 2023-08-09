<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Destination;
use App\Seo;
use Illuminate\Support\Facades\Log;

class DestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $destinations = Destination::get()->toArray();
        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.destinations.add');
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
            'name' => 'required',
            'seo.social_image' => "nullable|mimes:jpeg,jpg,png,gif|max:10000"
        ]);

        $status = 0;
        $msg = "";
        $destination = new Destination;
        $destination->name = $request->name;
        $destination->description = $request->description;
        $destination->slug = $this->create_slug_title($destination->name);
        $destination->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $destination->image_name = $imageName;
            $destination->image_type = $imageType;
            $destination->image_size = $imageSize;
        }

        if ($destination->save()) {
            // save seo
            if ($request->seo) {
                $this->createSeo($request->seo, $destination);
            }

            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($destination->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/destinations/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $destination['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $destination['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }
            $status = 1;
            $msg = "Destination created successfully.";
            session()->flash('message', $msg);
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $destination = Destination::with('seo')->find($id);
        return view('admin.destinations.edit', compact('destination'));
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
            'name' => 'required',
            'seo.social_image' => "nullable|mimes:jpeg,jpg,png,gif|max:10000"
        ]);

        $status = 0;
        $msg = "";
        $destination = Destination::find($request->id);
        $destination->name = $request->name;
        $destination->description = $request->description;
        $destination->slug = $this->create_slug_title($destination->name);
        $destination->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $destination->image_name = $imageName;
            $destination->image_type = $imageType;
            $destination->image_size = $imageSize;
        }

        if ($destination->save()) {
            // update seo
            $this->updateSeo($request->seo, $destination);

            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/destinations/';
                Storage::deleteDirectory($path . $destination->id);

                $image_quality = 100;

                if (($destination->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/destinations/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $destination['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $destination['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            } else {
                if (isset($request->cropped_data) && !empty($request->cropped_data) && $destination->image_name) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/destinations/';
                    $image = Image::make(Storage::get('public/destinations/' . $destination->id . '/' . $destination->image_name));

                    Storage::deleteDirectory($path . $destination->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($destination->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($destination->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $destination['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(300, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $destination['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $destination->image_name = $imageNameUniqid;
                    $destination->save();
                }
            }

            $status = 1;
            $msg = "Destination created successfully.";
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
        $path = 'public/destinations/';

        if (Destination::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Destination has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function destinationList()
    {
        $destinations = Destination::all();
        return response()->json([
            'data' => $destinations
        ]);
    }

    public function createSeo($request, $destination)
    {
        $seo = new Seo;
        $seo->meta_title = $request['meta_title'];
        $seo->meta_keywords = $request['meta_keywords'];
        $seo->canonical_url = $request['canonical_url'];
        $seo->meta_description = $request['meta_description'];
        $seo->seoable_id = $destination->id;
        $seo->seoable_type = "destination";

        if ($seo->save()) {
            if (isset($request['social_image']) && !empty($request['social_image'])) {
                $social_image = $request['social_image'];
                $socialImageName = $social_image->getClientOriginalName();
                $socialImageFileSize = $social_image->getClientSize();
                $socialImageType = $social_image->getClientOriginalExtension();
                $socialImageNameUniqid = md5(microtime()) . '.' . $socialImageType;
                $socialImageName = $socialImageNameUniqid;
                $seo->social_image = $socialImageName;

                $image_quality = 100;
                if (($socialImageFileSize/1000000) > 1) {
                    $image_quality = 75;
                }

                $path = 'public/seos/';
                $image = Image::make($social_image);

                // store new image
                Storage::put($path . $seo->id . '/' . $socialImageName, (string) $image->encode('jpg', $image_quality));
                $file = $path . $seo->id.'/'. $socialImageName;

                $seo->save();
            }
            return 1;
        }

        return 0;
    }
    
    public function updateSeo($request, $destination)
    {
        if ($destination->seo) {
            $seo = $destination->seo;
        } else {
            $seo = new Seo;
            $seo->seoable_id = $destination->id;
            $seo->seoable_type = "destination";
        }

        $seo->meta_title = $request['meta_title'];
        $seo->meta_keywords = $request['meta_keywords'];
        $seo->canonical_url = $request['canonical_url'];
        $seo->meta_description = $request['meta_description'];

        if ($seo->save()) {
            if (isset($request['social_image']) && !empty($request['social_image'])) {
                $social_image = $request['social_image'];
                $social_image_name = $social_image->getClientOriginalName();
                $old_social_image_name = $seo->social_image;
                $socialImageFileSize = $social_image->getClientSize();
                $socialImageType = $social_image->getClientOriginalExtension();
                $social_image_name = md5(microtime()) . '.' . $socialImageType;
                $seo->social_image = $social_image_name;

                $image_quality = 100;
                if (($socialImageFileSize/1000000) > 1) {
                    $image_quality = 75;
                }

                $path = 'public/seos/';
                $image = Image::make($social_image);

                // store new image
                Storage::put($path . $seo->id . '/' . $social_image_name, (string) $image->encode('jpg', $image_quality));
                // delete old image
                Storage::delete($path . $seo->id . '/'. $old_social_image_name);

                $file = $path . $seo->id.'/'. $social_image_name;
                if (!Storage::exists($file)) {
                    $seo->social_image = "";
                    $seo->save();
                }

                $seo->save();
            }
            return 1;
        }

        return 0;
    }
}
