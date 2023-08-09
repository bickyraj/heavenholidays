<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Helpers\Setting;
use App\Invoice;
use App\Services\Recaptcha\RecaptchaService;
use App\Trip;
use App\TripDeparture;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Bickyraj\Hbl\Api\HblPayment;
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
            $trip = Trip::find($request->id);
            // save data to database.
            $invoice = new Invoice();
            $latest_invoice = DB::table('invoices')->latest('id')->first();
            $last_id = $latest_invoice ? $latest_invoice->id : 1;
            $invoice_number = str_pad($last_id, 5, "0", STR_PAD_LEFT);
            $invoice_id = 'IV-' . $invoice_number;
            $invoice->invoice_id = $invoice_id;
            $invoice->full_name = $request->first_name;
            // price is 25% of the booking amount
            $trip_offer_price = floatval($trip->offer_price);
            $trip_cost_price = floatval($trip->cost);
            $trip_price = ($trip_offer_price != 0) ? $trip_offer_price : $trip_cost_price;

            $trip_rate = 0.25;
            if ($request->payment_type == "full") {
                $trip_rate = 1;
            }
            $price_after_percent = $trip_rate * $trip_price * intval($request->no_of_travellers);
            $invoice->amount = $price_after_percent;
            $invoice->price = $price_after_percent;
            $invoice->trip_name = $trip->name;
            $invoice->email = $request->email;
            $invoice->contact_number = $request->contact_no;
            $invoice->ref_id = $invoice_number;
            $invoice->save();

            // payment
            $payment = [];
            $payment['formID'] = config('hbl.OfficeId');
            $payment['api_key'] = config('hbl.AccessToken');
            $payment['input_currency'] = config('hbl.InputCurrencty');
            $payment['merchant_id'] = config('hbl.OfficeId');
            $payment['input_amount'] = $invoice->amount;
            $payment['input_3d'] = config('hbl.Input3DS');
            $payment['simple_spc'] = config('hbl.OfficeId');
            $payment['fail_url'] = route('hbl.payment.failed');
            $payment['cancel_url'] = route('hbl.payment.canceled');
            $payment['success_url'] = route('front.payment.callback', ['invoceId' => $invoice->invoice_id]);
            $payment['backend_url'] = route('home');
            $payment['invoiceNo'] = $invoice->invoice_id;
            $payment['ref_id'] = $invoice->ref_id;
            //echo "Payment jose request \n ";
            $paymentObj = [
                "order_no" => $payment['ref_id'],
                "amount" => $payment['input_amount'],
                "success_url" => $payment['success_url'],
                "failed_url" => $payment['fail_url'],
                "cancel_url" => $payment['cancel_url'],
                "backend_url" => $payment['backend_url'],
                "custom_fields" => [
                    'RefID' => $payment['ref_id']
                ],
            ];
            HblPayment::pay($paymentObj);
        } catch (\Throwable $th) {
            \Log::info($th->getMessage());
            return redirect()->back();
        }
    }

    public function storePaymentFromFooter(Request $request)
    {
        try {
            // save data to database.
            $invoice = new Invoice();
            $latest_invoice = DB::table('invoices')->latest('id')->first();
            $last_id = $latest_invoice ? $latest_invoice->id : 1;
            $invoice_number = str_pad($last_id, 5, "0", STR_PAD_LEFT);
            $invoice_id = 'IV-' . $invoice_number;
            $invoice->invoice_id = $invoice_id;
            $invoice->full_name = $request->fullname;
            $price_float = floatval($request->price);
            $invoice->amount = $price_float;
            $invoice->price = $price_float;
            $invoice->trip_name = $request->trip_name;
            $invoice->email = $request->email;
            $invoice->contact_number = $request->contact_number;
            $invoice->ref_id = $invoice_number;
            $invoice->save();

            // payment
            $payment = [];
            $payment['formID'] = config('hbl.OfficeId');
            $payment['api_key'] = config('hbl.AccessToken');
            $payment['input_currency'] = config('hbl.InputCurrencty');
            $payment['merchant_id'] = config('hbl.OfficeId');
            $payment['input_amount'] = $invoice->amount;
            $payment['input_3d'] = config('hbl.Input3DS');
            $payment['simple_spc'] = config('hbl.OfficeId');
            $payment['fail_url'] = route('hbl.payment.failed');
            $payment['cancel_url'] = route('hbl.payment.canceled');
            $payment['success_url'] = route('front.payment.callback', ['invoceId' => $invoice->invoice_id]);
            $payment['backend_url'] = route('home');
            $payment['invoiceNo'] = $invoice->invoice_id;
            $payment['ref_id'] = $invoice->ref_id;
            $paymentObj = [
                "order_no" => $payment['ref_id'],
                "amount" => $payment['input_amount'],
                "success_url" => $payment['success_url'],
                "failed_url" => $payment['fail_url'],
                "cancel_url" => $payment['cancel_url'],
                "backend_url" => $payment['backend_url'],
                "custom_fields" => [
                    'RefID' => $payment['ref_id']
                ],
            ];
            HblPayment::pay($paymentObj);
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

    public function paymentSuccess(Request $request)
    {
        $invoice = Invoice::where('ref_id', $request->orderNo)->first();
        $invoice->status = Invoice::PAID;
        $invoice->save();
        Session::flash('success_message', 'Payment successfull.');
        return redirect()->route('home');
    }

    public function paymentCanceled(Request $request)
    {
        $invoice = Invoice::where('ref_id', $request->orderNo)->first();
        $invoice->status = Invoice::CANCELED;
        $invoice->save();
        Session::flash('error_message', 'Payment Canceled. Please try again.');
        return redirect()->route('home');
    }

    public function paymentFailed(Request $request)
    {
        // update invoice data
        $invoice = Invoice::where('ref_id', $request->orderNo)->first();
        $invoice->status = Invoice::FAILED;
        $invoice->save();
        Session::flash('error_message', 'Payment failed. Please try again.');
        return redirect()->route('home');
    }

    public function makePayment()
    {
        return view('front.payment.payment');
    }
}
