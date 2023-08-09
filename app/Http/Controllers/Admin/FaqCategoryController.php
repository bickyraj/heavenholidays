<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\FaqCategory;

class FaqCategoryController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index()
	{
	    $categories = FaqCategory::get()->toArray();
	    return view('admin.faqCategories.index', compact('categories'));
	}

	public function categoryList()
	{
	    $categories = FaqCategory::all();
	    return response()->json([
	        'data' => $categories
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
			'name' => 'required|unique:faq_categories,name',
		], [
			'name.unique' => 'The name has already been taken.'
		]);

	    $status = 0;
	    $msg = "";
	    $category = new FaqCategory;
	    $category->name = $request->name;

	    if ($category->save()) {
	        $status = 1;
	        $msg = "Category created successfully.";
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

	    if (FaqCategory::find($id)->delete()) {
	        $status = 1;
	        $http_status_code = 200;
	        $msg = "Category has been deleted";
	    }

	    return response()->json([
	        'status' => $status,
	        'message' => $msg
	    ], $http_status_code);
	}
}
