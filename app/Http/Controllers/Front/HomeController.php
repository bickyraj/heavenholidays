<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Setting;
use App\Invoice;
use App\Services\Recaptcha\RecaptchaService;
use App\TripDeparture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        // $data['banners'] = \App\Trip::latest()->limit(5)->get();
        $data['destinations'] = \App\Destination::orderBy('name')->select('id', 'name', 'slug', 'image_name')->get();
        $data['activities'] = \App\Activity::orderBy('id')->select('id', 'name', 'slug', 'image_name')->get();
        $data['block_1_trips'] = \App\Trip::where('block_1', 1)->latest()->get();
        $data['block_2_trips'] = \App\Trip::where('block_2', 1)->latest()->get();
        $data['block_3_trips'] = \App\Trip::where('block_3', 1)->latest()->get();
        $data['reviews'] = \App\TripReview::latest()->limit(2)->published()->get();
        $data['blogs'] = \App\Blog::latest()->limit(2)->get();
        $data['why_chooses'] = \App\WhyChoose::latest()->limit(6)->get();
        $data['departures'] = TripDeparture::where([
            ['status', 1],
            ['from_date', '>=', Carbon::today()]
        ])->orderBy('from_date', 'asc')->limit(10)->get();

        return view('front.index', $data);
    }

    public function faqs()
    {
        $faq_categories = \App\FaqCategory::with('faqs')->get();
        // $faqs = \App\Faq::where('status', '=', 1)->get();
        return view('front.faqs.index', compact('faq_categories'));
    }

    public function reviews()
    {
        $trips = \App\Trip::orderBy('name', 'asc')->select('id', 'name')->get();
        $reviews = \App\TripReview::latest()->published()->paginate(10);
        return view('front.reviews.index', compact('trips', 'reviews'));
    }

    public function contact()
    {
        return view('front.contacts.index');
    }

    public function contactStore(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $verifiedRecaptcha = RecaptchaService::verifyRecaptcha($request->get('g-recaptcha-response'));

        if (!$verifiedRecaptcha) {
            session()->flash('error_message', 'Google recaptcha error.');
            return redirect()->back();
        }

        try {
            $request->merge([
                'ip_address' => $request->ip()
            ]);
            Mail::send('emails.contact', ['body' => $request], function ($message) use ($request) {
                $message->to(Setting::get('email'));
                $message->from($request->email);
                $message->subject('Enquiry');
            });
            session()->flash('success_message', "Thank you for your enquiry. We'll contact you very soon.");
            $prev_route = app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName();

            if ($request->redirect_url) {
                return redirect()->to($request->redirect_url);
            }
        } catch (\Exception $e) {
            Log::info($e->getMessage());
            session()->flash('error_message', __('alerts.save_error'));
        }
        return redirect()->route('front.contact.index');
    }

    public function verifyRecaptcha($recaptcha)
    {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        // $data = [
        //     'secret' => config('constants.recaptcha.secret'),
        //     'response' => $recaptcha
        // ];

        // $options = [
        //     'http' => [
        //         'header'  => "Content-type: application/x-www-form-urlencode\r\n",
        //         'method'  => 'POST',
        //         'content' => http_build_query($data)
        //     ]
        // ];

        // $context = stream_context_create($options);
        // $result = file_get_contents($url, false, $context);
        // $resultJson = json_decode($result);

        $recaptcha = file_get_contents($url . '?secret=' . config('constants.recaptcha.secret') . '&response=' . $recaptcha);
        $resultJson = json_decode($recaptcha);

        $valid = false;

        if ($resultJson->success) {
            if ($resultJson->score >= 0.5) {
                $valid = true;
            }
        }

        return $valid;
    }

    public function payment()
    {
        return view('front.payment.payment');
    }

    public function storePayment(Request $request)
    {
        try {
            // save data to database.
            $invoice = new Invoice();
            $latest_invoice = DB::table('invoices')->latest('id')->first();
            $last_id = $latest_invoice ? $latest_invoice->id : 1;
            $invoice_id = 'IV-' .
                str_pad($last_id, 5, "0", STR_PAD_LEFT);
            $invoice->invoice_id = $invoice_id;
            $invoice->full_name = $request->fullname;
            $invoice->amount = $request->amount;
            $invoice->price = $request->price;
            $invoice->trip_name = $request->trip_name;
            $invoice->email = $request->email;
            $invoice->contact_number = $request->contact_number;
            $invoice->save();
            return redirect()->route('front.redeem_payment', ['id' => $invoice->id]);
        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
            return redirect()->back();
        }
    }

    public function redeemPayment($id)
    {
        $invoice = Invoice::find($id);
        $payment = [];
        $payment['paymentGatewayID'] = config('constants.payment_merchant_id');
        $payment['invoiceNo'] = $invoice->invoice_id;
        $payment['productDesc'] = $invoice->trip_name;
        $payment['price'] =
            str_pad((float) $invoice->price * 100, 12, "0", STR_PAD_LEFT);
        $payment['currencyCode'] = "840";
        $payment['nonSecure'] = "N";
        $payment['hashValue'] = config('constants.payment_merchant_key');

        return view('front.payment.redeem_payment', compact('payment'));
    }
}
