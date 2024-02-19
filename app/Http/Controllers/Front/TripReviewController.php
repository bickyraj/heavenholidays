<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Recaptcha\RecaptchaService;
use App\Trip;
use Illuminate\Http\Request;
use App\TripReview;
use Illuminate\Support\Facades\Storage;
use Image;
use Illuminate\Support\Facades\Log;

class TripReviewController extends Controller
{
    public function create()
    {
        $data['trips'] = Trip::where('status', 1)->get();
        return view('front.reviews.create', $data);
    }

	public function store(Request $request)
	{
        $request->merge(['review_country' => $request->country]);
		$request->validate([
		    'review_name' => 'required',
        ]);

        $verifiedRecaptcha = RecaptchaService::verifyRecaptcha($request->get('g-recaptcha-response'));
        if (!$verifiedRecaptcha) {
            session()->flash('error_message', 'Google recaptcha error.');
            return redirect()->back();
        }

		if ($request->rating == null) {
			$request->merge(["rating" => 5]);
		}

		$review = new TripReview;
		$review->fill($request->except('image', 'g-recaptcha-response'));

		if ($request->hasFile('image')) {
		    $imageName = $request->image->getClientOriginalName();
		    $imageFileSize = $request->image->getClientSize();
		    $imageImageType = $request->image->getClientOriginalExtension();
		    $imageNameUniqid = md5($imageName . microtime()) . '.' . $imageImageType;
		    $imageName = $imageNameUniqid;
		    $review->image_name = $imageName;
		}

		if ($review->save()) {

            if ($request->hasFile('image')) {

                $image_quality = 100;

                if (($imageFileSize/1000000) > 1) {
                    $image_quality = 75;
                }

                $path = 'public/trip-reviews/';
                $image = Image::make($request->image);
                Storage::put($path . $review['id'] . '/' . $imageName, (string) $image->encode('jpg', $image_quality));
                $file = $path . $review['id'].'/'. $imageName;

                // thumbnail image
                $image->fit(100, 100, function ($constraint) {
                    $constraint->aspectRatio();
                });
                Storage::put($path . $review['id'] . '/thumb_' . $imageName, (string) $image->encode('jpg', $image_quality));

                if (!Storage::exists($file)) {
                    $review->image_name = "";
                    $review->save();
                }
            }

			$status = 1;
			$msg = "Thank you for your review. Have a nice day!";
			session()->flash('message', $msg);
		}

		return redirect()->back();
		// return redirect()->route('front.reviews.index');
	}
}
