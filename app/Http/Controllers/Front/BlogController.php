<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Blog;

class BlogController extends Controller
{
	public function index()
	{
		$blogs = Blog::latest()->get();
		return view('front.blogs.index', compact('blogs'));
	}

	public function show($slug)
	{
		$blog = Blog::where('slug', '=', $slug)->first();
		$blogs = Blog::limit(3)->latest()->get();

		return view('front.blogs.show', compact('blog', 'blogs'));
	}
}
