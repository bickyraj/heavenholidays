<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Region;
use App\Seo;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $regions = Region::get()->toArray();
        return view('admin.regions.index', compact('regions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$destinations = \App\Destination::all();
        $activities = \App\Activity::all();
        return view('admin.regions.add', compact('destinations', 'activities'));
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
        $region = new Region;
        $region->name = $request->name;
        $region->description = $request->description;
        $region->slug = $this->create_slug_title($region->name);
        $region->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $region->image_name = $imageName;
            $region->image_type = $imageType;
            $region->image_size = $imageSize;
        }

        if ($region->save()) {
            // save seo
            if ($request->seo) {
                $this->createSeo($request->seo, $region);
            }

            // save destination to the region_destination table
            if ($request->destinations) {
                $region->destinations()->attach($request->destinations);
            }

            // save activities to the region_destination table
            if ($request->activities) {
                $region->activities()->attach($request->activities);
            }
            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($region->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/regions/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $region['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $region['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }
            $status = 1;
            $msg = "Region created successfully.";
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
        $region = Region::with([
            'destinations' => function($q) {
                $q->pluck('destination_id');
            },
            'seo'
        ])->find($id);

        $destination_ids = $region->destinations->pluck('id')->toArray();
        $destinations = \App\Destination::all();
        $activity_ids = $region->activities->pluck('id')->toArray();
        $activities = \App\Activity::all();
        return view('admin.regions.edit', compact('region', 'destinations', 'destination_ids', 'activities', 'activity_ids'));
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
        $region = Region::find($request->id);
        $region->name = $request->name;
        $region->description = $request->description;
        $region->slug = $this->create_slug_title($region->name);
        $region->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $region->image_name = $imageName;
            $region->image_type = $imageType;
            $region->image_size = $imageSize;
        }

        if ($region->save()) {
            // update seo
            $this->updateSeo($request->seo, $region);

            // remove and add new destinations
            if ($request->destinations) {
                $region->destinations()->detach();
                $region->destinations()->attach($request->destinations);
            }

            // remove and add new activities
            if ($request->activities) {
                $region->activities()->detach();
                $region->activities()->attach($request->activities);
            }

            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/regions/';
                Storage::deleteDirectory($path . $region->id);

                $image_quality = 100;

                if (($region->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/regions/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $region['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $region['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            } else {
                if (isset($request->cropped_data) && !empty($request->cropped_data) && $region->image_name) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/regions/';
                    $image = Image::make(Storage::get('public/regions/' . $region->id . '/' . $region->image_name));

                    Storage::deleteDirectory($path . $region->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($region->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($region->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $region['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(300, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $region['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $region->image_name = $imageNameUniqid;
                    $region->save();
                }
            }

            $status = 1;
            $msg = "Region created successfully.";
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
        $path = 'public/regions/';

        if (Region::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Region has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function regionList()
    {
        $regions = Region::all();
        return response()->json([
            'data' => $regions
        ]);
    }

    public function createSeo($request, $region)
    {
        $seo = new Seo;
        $seo->meta_title = $request['meta_title'];
        $seo->meta_keywords = $request['meta_keywords'];
        $seo->canonical_url = $request['canonical_url'];
        $seo->meta_description = $request['meta_description'];
        $seo->seoable_id = $region->id;
        $seo->seoable_type = "region";

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
    
    public function updateSeo($request, $region)
    {
        if ($region->seo) {
            $seo = $region->seo;
        } else {
            $seo = new Seo;
            $seo->seoable_id = $region->id;
            $seo->seoable_type = "region";
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
