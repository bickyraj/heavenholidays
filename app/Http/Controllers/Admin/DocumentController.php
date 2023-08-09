<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Document;

class DocumentController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $documents = Document::get()->toArray();
	    return view('admin.documents.index', compact('documents'));
	}

	public function documentList()
	{
	    $documents = Document::all();
	    return response()->json([
	        'data' => $documents
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
			'name' => 'required',
            'file' => "required|mimes:jpeg,jpg,png,gif|max:10000",
		]);

	    $status = 0;
	    $msg = "";
	    $document = new Document;
	    $document->name = $request->name;

	    if ($request->hasFile('file')) {
	        $imageName = $request->file->getClientOriginalName();
	        $imageSize = $request->file->getClientSize();
	        $imageType = $request->file->getClientOriginalExtension();
	        $imageName = md5(microtime()) . '.' . $imageType;
	        $document->file = $imageName;
	    }

	    if ($document->save()) {
	        // save image.
	        if ($request->hasFile('file')) {

	            $image_quality = 100;

	            if (($document->image_size/1000000) > 1) {
	                $image_quality = 75;
	            }

	            $path = 'public/documents/';

	            $image = Image::make($request->file);
	            Storage::put($path . $document['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));
	            $status = 1;
	        }
	        $status = 1;
	        $msg = "Document created successfully.";
            session()->flash('success_message', $msg);
	    }
        return redirect()->back();
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
	    $path = 'public/documents/';

	    if (Document::find($id)->delete()) {
	        Storage::deleteDirectory($path . $id);
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "Document has been deleted";
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ], $http_status_code);
	}
}
