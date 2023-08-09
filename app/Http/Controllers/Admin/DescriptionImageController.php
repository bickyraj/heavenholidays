<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;
use Illuminate\Support\Facades\Storage;

class DescriptionImageController extends Controller
{
    public function saveDescImage(Request $request)
    {
        if ($request->hasFile('file')) {
            $imageName = $request->file->getClientOriginalName();
            $imageType = $request->file->getClientOriginalExtension();
            $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageType;
            $imageName = $imageNameUniqid;
            $path = 'public/description-images/';
            $image = Image::make($request->file);
            Storage::put($path . $imageName, (string) $image->encode('jpg', 100));
        }
        $image_url = url('/storage/description-images');
        return response()->json([
            'data' => $image_url . '/' . $imageName,
            'status' => 1,
            'message' => 'image saved.'
        ]);
    }

    public function deleteDescImage(Request $request)
    {
        $image_name = array_slice(explode('/', $request->src), -1)[0];
        Storage::delete('/public/description-images/' . $image_name);
        return response()->json([
            'status' => 1,
            'message' => 'image deleted.'
        ]);
    }
}
