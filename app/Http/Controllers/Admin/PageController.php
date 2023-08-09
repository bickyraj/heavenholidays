<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Page;
use App\Seo;
use Illuminate\Support\Facades\Log;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Page::get()->toArray();
        return view('admin.pages.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.add');
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
        $page = new Page;
        $page->name = $request->name;
        $page->description = $request->description;
        $page->slug = $this->create_slug_title($page->name);
        $page->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $page->image_name = $imageName;
            $page->image_type = $imageType;
            $page->image_size = $imageSize;
        }

        if ($page->save()) {
            // save seo
            if ($request->seo) {
                $this->createSeo($request->seo, $page);
            }

            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($page->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/pages/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $page['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $page['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }
            $status = 1;
            $msg = "Page created successfully.";
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
        $page = Page::with('seo')->find($id);
        return view('admin.pages.edit', compact('page'));
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
        $page = Page::find($request->id);
        $page->name = $request->name;
        $page->description = $request->description;
        $page->slug = $this->create_slug_title($page->name);
        $page->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $page->image_name = $imageName;
            $page->image_type = $imageType;
            $page->image_size = $imageSize;
        }

        if ($page->save()) {
            // update seo
            $this->updateSeo($request->seo, $page);

            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/pages/';
                Storage::deleteDirectory($path . $page->id);

                $image_quality = 100;

                if (($page->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/pages/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $page['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(300, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $page['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            } else {
                if (isset($page['image_name']) && isset($request->cropped_data) && !empty($request->cropped_data)) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/pages/';
                    $image = Image::make(Storage::get('public/pages/' . $page->id . '/' . $page->image_name));

                    Storage::deleteDirectory($path . $page->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($page->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($page->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $page['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(300, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $page['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $page->image_name = $imageNameUniqid;
                    $page->save();
                }
            }
            
            $status = 1;
            $msg = "Page updated successfully.";
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
        $path = 'public/pages/';

        if (Page::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Page has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function pageList()
    {
        $pages = Page::all();
        return response()->json([
            'data' => $pages
        ]);
    }

    public function createSeo($request, $page)
    {
        $seo = new Seo;
        $seo->meta_title = $request['meta_title'];
        $seo->meta_keywords = $request['meta_keywords'];
        $seo->canonical_url = $request['canonical_url'];
        $seo->meta_description = $request['meta_description'];
        $seo->seoable_id = $page->id;
        $seo->seoable_type = "page";

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
    
    public function updateSeo($request, $page)
    {
        if ($page->seo) {
            $seo = $page->seo;
        } else {
            $seo = new Seo;
            $seo->seoable_id = $page->id;
            $seo->seoable_type = "page";
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
