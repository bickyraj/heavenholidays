<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Blog;
use App\Seo;
use Image;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $blogs = Blog::get()->toArray();
        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.blogs.add');
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
            'blog_date' => 'date|nullable|date_format:Y-m-d'
        ]);

        $status = 0;
        $msg = "";
        $blog = new Blog;
        $blog->name = $request->name;
        $blog->description = $request->description;
        $blog->blog_date = $request->blog_date;
        $blog->slug = $this->create_slug_title($blog->name);
        $blog->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $blog->image_name = $imageName;
            $blog->image_type = $imageType;
            $blog->image_size = $imageSize;
        }

        if ($blog->save()) {
            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($blog->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/blogs/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $blog['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // medium image
                $image->fit(600, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path . $blog['id'] . '/medium_' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(200, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path . $blog['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));

                $status = 1;
            }
            $status = 1;
            $msg = "Blog created successfully.";
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
        $blog = Blog::find($id);
        return view('admin.blogs.edit', compact('blog'));
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
            'blog_date' => 'date|nullable|date_format:Y-m-d'
        ]);

        $status = 0;
        $msg = "";
        $blog = Blog::find($request->id);
        $blog->name = $request->name;
        $blog->description = $request->description;
        $blog->blog_date = $request->blog_date;
        $blog->slug = $this->create_slug_title($blog->name);
        $blog->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $blog->image_name = $imageName;
            $blog->image_type = $imageType;
            $blog->image_size = $imageSize;
        }

        if ($blog->save()) {
            // update seo
            $this->updateSeo($request->seo, $blog);
            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/blogs/';
                Storage::deleteDirectory($path . $blog->id);

                $image_quality = 100;

                if (($blog->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/blogs/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $blog['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // medium image
                $image->fit(600, 300, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path . $blog['id'] . '/medium_' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(200, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path . $blog['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));

                $status = 1;
            } else {
                if (isset($blog['image_name']) && isset($request->cropped_data) && !empty($request->cropped_data)) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/blogs/';
                    $image = Image::make(Storage::get('public/blogs/' . $blog->id . '/' . $blog->image_name));

                    Storage::deleteDirectory($path . $blog->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($blog->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($blog->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $blog['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // medium image
                    $image->fit(600, 300, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path . $blog['id'] . '/medium_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(200, 100, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    Storage::put($path . $blog['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $blog->image_name = $imageNameUniqid;
                    $blog->save();
                }
            }

            $status = 1;
            $msg = "Blog updated successfully.";
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
        $path = 'public/blogs/';

        if (Blog::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Blog has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function blogList()
    {
        $blogs = Blog::all();
        return response()->json([
            'data' => $blogs
        ]);
    }

    public function updateSeo($request, $blog)
    {
        if ($blog->seo) {
            $seo = $blog->seo;
        } else {
            $seo = new Seo;
            $seo->seoable_id = $blog->id;
            $seo->seoable_type = "blog";
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
                if (($socialImageFileSize / 1000000) > 1) {
                    $image_quality = 75;
                }

                $path = 'public/seos/';
                $image = Image::make($social_image);

                // store new image
                Storage::put($path . $seo->id . '/' . $social_image_name, (string) $image->encode('jpg', $image_quality));
                // delete old image
                Storage::delete($path . $seo->id . '/' . $old_social_image_name);

                $file = $path . $seo->id . '/' . $social_image_name;
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
