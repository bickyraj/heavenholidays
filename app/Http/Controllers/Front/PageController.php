<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Page;

class PageController extends Controller
{
    public function about()
	{
		$page = Page::where('slug', '=', 'about-us')->first();

		if ($page) {
			return view('front.pages.about', compact('page'));
		}

		return abort(404);
	}
	
	public function show($slug)
	{
		$page = Page::where('slug', '=', $slug)->first();

		if ($page) {
			return view('front.pages.show', compact('page'));
		}

		return abort(404);
	}
}
