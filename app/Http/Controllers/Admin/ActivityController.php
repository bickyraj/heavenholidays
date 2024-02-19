<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Activity;
use App\Seo;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activities = Activity::get()->toArray();
        return view('admin.activities.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$destinations = \App\Destination::all();
        return view('admin.activities.add', compact('destinations'));
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
        $activity = new Activity;
        $activity->name = $request->name;
        $activity->description = $request->description;
        $activity->slug = $this->create_slug_title($activity->name);
        $activity->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $activity->image_name = $imageName;
            $activity->image_type = $imageType;
            $activity->image_size = $imageSize;
        }

        if ($activity->save()) {
            // save seo
            if ($request->seo) {
                $this->createSeo($request->seo, $activity);
            }

            // save destination to the activity_destination table
            if ($request->destinations) {
                $activity->destinations()->attach($request->destinations);
            }
            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($activity->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/activities/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $activity['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // medium image
                $image->fit(615, 462, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $activity['id'] . '/medium_' . $imageName, (string) $image->encode('jpg', $image_quality));
                
                // thumbnail image
                $image->fit(360, 270, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $activity['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }
            $status = 1;
            $msg = "Activity created successfully.";
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
        $activity = Activity::with([
            'destinations' => function($q) {
                $q->pluck('destination_id');
            },
            'seo'
        ])->find($id);

        $destination_ids = $activity->destinations->pluck('id')->toArray();
        $destinations = \App\Destination::all();
        return view('admin.activities.edit', compact('activity', 'destinations', 'destination_ids'));
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
        $activity = Activity::find($request->id);
        $activity->name = $request->name;
        $activity->description = $request->description;
        $activity->slug = $this->create_slug_title($activity->name);
        $activity->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $activity->image_name = $imageName;
            $activity->image_type = $imageType;
            $activity->image_size = $imageSize;
        }

        if ($activity->save()) {

            $this->updateSeo($request->seo, $activity);

            if ($request->destinations) {
                $activity->destinations()->detach();
                $activity->destinations()->attach($request->destinations);
            }

            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/activities/';
                Storage::deleteDirectory($path . $activity->id);

                $image_quality = 100;

                if (($activity->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/activities/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $activity['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));
                
                // medium image
                $image->fit(615, 462, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $activity['id'] . '/medium_' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(360, 270, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $activity['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            } else {
                if (isset($request->cropped_data) && !empty($request->cropped_data) && $activity->image_name) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/activities/';
                    $image = Image::make(Storage::get('public/activities/' . $activity->id . '/' . $activity->image_name));

                    Storage::deleteDirectory($path . $activity->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($activity->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($activity->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $activity['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));
                    
                    // medium image
                    $image->fit(615, 462, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path . $activity['id'] . '/medium_' . $imageName, (string) $image->encode('jpg', $image_quality));

                    // thumbnail image
                    $image->fit(360, 270, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $activity['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $activity->image_name = $imageNameUniqid;
                    $activity->save();
                }
            }

            $status = 1;
            $msg = "Activity created successfully.";
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
        $path = 'public/activities/';

        if (Activity::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Activity has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function activityList()
    {
        $activities = Activity::all();
        return response()->json([
            'data' => $activities
        ]);
    }

    public function createSeo($request, $activity)
    {
        $seo = new Seo;
        $seo->meta_title = $request['meta_title'];
        $seo->meta_keywords = $request['meta_keywords'];
        $seo->canonical_url = $request['canonical_url'];
        $seo->meta_description = $request['meta_description'];
        $seo->seoable_id = $activity->id;
        $seo->seoable_type = "activity";

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

    public function updateSeo($request, $activity)
    {
        if ($activity->seo) {
            $seo = $activity->seo;
        } else {
            $seo = new Seo;
            $seo->seoable_id = $activity->id;
            $seo->seoable_type = "activity";
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
