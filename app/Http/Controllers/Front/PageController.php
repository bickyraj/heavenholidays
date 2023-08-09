<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Page;

class PageController extends Controller
{
	public function show($slug)
	{
		$page = Page::where('slug', '=', $slug)->first();

		if ($page) {
			return view('front.pages.show', compact('page'));
		}

		return abort(404);
	}
}
