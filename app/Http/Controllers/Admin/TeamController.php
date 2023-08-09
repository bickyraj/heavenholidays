<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Team;
use App\TeamCertificate;
use App\TeamGallery;
use Illuminate\Support\Str;
use Image;
use Illuminate\Support\Facades\Log;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teams = Team::get()->toArray();
        return view('admin.teams.index', compact('teams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.teams.add');
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
            'name' => 'required'
        ]);

        $status = 0;
        $msg = "";
        $team = new Team;
        $team->name = $request->name;
        $team->description = $request->description;
        $team->position = $request->position;
        $team->sort_order = 0;
        $team->type = $request->type;
        $team->slug = $this->create_slug_title($team->name);
        $team->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $team->image_name = $imageName;
            $team->image_type = $imageType;
            $team->image_size = $imageSize;
        }

        if ($team->save()) {
            // save image.
            if ($request->hasFile('file')) {

                $image_quality = 100;

                if (($team->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/teams/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $team['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $team['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            }

            // save files.
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $certificate = new TeamCertificate();
                    // save mother signature file.
                    $fileOriginal = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $filename = time() . '_' . Str::random(5) . '_' . Str::slug($filename) . '.' . $extension;

                    $image = Image::make($file);
                    $document_file_path = '/public/teams/' . $team['id'] . '/certificates/';
                    Storage::put($document_file_path . $filename, (string) $image->encode('jpg', 100));
                    $certificate->file = $filename;
                    $team->certificates()->save($certificate);
                }
            }

            // save galleries.
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    $gallery = new TeamGallery();
                    // save mother signature file.
                    $fileOriginal = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $filename = time() . '_' . Str::random(5) . '_' . Str::slug($filename) . '.' . $extension;

                    $image = Image::make($file);
                    $document_file_path = '/public/teams/' . $team['id'] . '/galleries/';
                    Storage::put($document_file_path . $filename, (string) $image->encode('jpg', 100));
                    $gallery->file = $filename;
                    $team->galleries()->save($gallery);
                }
            }

            $status = 1;
            $msg = "Team created successfully.";
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
        $team = Team::find($id);
        return view('admin.teams.edit', compact('team'));
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
            'name' => 'required'
        ]);

        $status = 0;
        $msg = "";
        $team = Team::find($request->id);
        $team->name = $request->name;
        $team->description = $request->description;
        $team->position = $request->position;
        $team->type = $request->type;
        $team->slug = $this->create_slug_title($team->name);
        $team->status = 1;

        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageSize = $request->file->getClientSize();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;

            $team->image_name = $imageName;
            $team->image_type = $imageType;
            $team->image_size = $imageSize;
        }

        if ($team->save()) {
            // save image.
            if ($request->hasFile('file')) {

                $path = 'public/teams/';
                Storage::deleteDirectory($path . $team->id);

                $image_quality = 100;

                if (($team->image_size/1000000) > 1) {
                    $image_quality = 75;
                }

                $cropped_data = json_decode($request->cropped_data, true);
                $path = 'public/teams/';

                $image = Image::make($request->file);

                // crop image
                $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                Storage::put($path . $team['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));

                // thumbnail image
                $image->fit(200, 200, function ($constraint) {
                    $constraint->aspectRatio();
                });

                Storage::put($path . $team['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));
                $status = 1;
            } else {
                if (isset($team['image_name']) && isset($request->cropped_data) && !empty($request->cropped_data)) {
                    $cropped_data = json_decode($request->cropped_data, true);

                    $path = 'public/teams/';
                    $image = Image::make(Storage::get('public/teams/' . $team->id . '/' . $team->image_name));

                    Storage::deleteDirectory($path . $team->id);

                    // crop image
                    $image->crop(round($cropped_data['width']), round($cropped_data['height']), round($cropped_data['x']), round($cropped_data['y']));

                    $ext = pathinfo($team->image_name, PATHINFO_EXTENSION);

                    $imageNameUniqid = md5($team->image_name . microtime()) . '.' . $ext;

                    Storage::put($path . $team['id'] . '/' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    // thumbnail image
                    $image->fit(200, 200, function ($constraint) {
                        $constraint->aspectRatio();
                    });

                    Storage::put($path . $team['id'] . '/thumb_' . $imageNameUniqid, (string) $image->encode('jpg', 100));

                    $team->image_name = $imageNameUniqid;
                    $team->save();
                }
            }

            // save files.
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $certificate = new TeamCertificate();
                    // save mother signature file.
                    $fileOriginal = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filesize = $file->getClientSize();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $filename = time() . '_' . Str::random(5) . '_' . Str::slug($filename) . '.' . $extension;

                    $image = Image::make($file);
                    $document_file_path = '/public/teams/' . $team['id'] . '/certificates/';
                    Storage::put($document_file_path . $filename, (string) $image->encode('jpg', 100));
                    $certificate->file = $filename;
                    $team->certificates()->save($certificate);
                }
            }

            // remove files if any.
            if (!empty($request->remove_files_arr)) {
                $remove_files = explode(',', $request->remove_files_arr);

                foreach ($remove_files as $file) {
                    $doc = TeamCertificate::find($file);
                    $fullImage = storage_path('app/public/teams/' . $team['id'] . '/certificates/' . $doc->file);
                    if(file_exists($fullImage) && is_file($fullImage)){
                        unlink($fullImage);
                    }
                    $doc->delete();
                }
            }

            // save galleries.
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $file) {
                    $gallery = new TeamGallery();
                    // save mother signature file.
                    $fileOriginal = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    $filesize = $file->getClientSize();
                    $filename = pathinfo($fileOriginal, PATHINFO_FILENAME);
                    $filename = time() . '_' . Str::random(5) . '_' . Str::slug($filename) . '.' . $extension;

                    $image = Image::make($file);
                    $document_file_path = '/public/teams/' . $team['id'] . '/galleries/';
                    Storage::put($document_file_path . $filename, (string) $image->encode('jpg', 100));
                    $gallery->file = $filename;
                    $team->galleries()->save($gallery);
                }
            }

            // remove galleries if any.
            if (!empty($request->remove_pictures_arr)) {
                $remove_files = explode(',', $request->remove_pictures_arr);

                foreach ($remove_files as $file) {
                    $doc = TeamGallery::find($file);
                    $fullImage = storage_path('app/public/teams/' . $team['id'] . '/galleries/' . $doc->file);
                    if(file_exists($fullImage) && is_file($fullImage)){
                        unlink($fullImage);
                    }
                    $doc->delete();
                }
            }

            $status = 1;
            $msg = "Team updated successfully.";
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
        $path = 'public/teams/';

        if (Team::find($id)->delete()) {
            Storage::deleteDirectory($path . $id);
            $status = 1;
            $http_status_code = 200;
            $msg = "Team has been deleted";
        }

        return response()->json([
            'status' => $status,
            'message' => $msg
        ], $http_status_code);
    }

    public function teamList()
    {
        $teams = Team::all();
        return response()->json([
            'data' => $teams
        ]);
    }
}
