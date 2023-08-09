<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Document;

class DocumentController extends Controller
{
	public function index()
	{
		$documents = Document::latest()->get();
		return view('front.documents.index', compact('documents'));
	}
}
