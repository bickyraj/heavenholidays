<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Helpers\Setting;
use Error;

class PaymentController extends Controller
{
    public function callbackPayment(Request $request, $invoice_id)
    {
        try {
            $order_number = $request->orderNo;
            $controller_internal_id = $request->controllerInternalId;
            $invoice = Invoice::where('invoice_id', $invoice_id)->first();
            $invoice->order_number = $order_number;
            $invoice->controller_internal_id = $controller_internal_id;
            if ($invoice->save()) {
                // send email to admin
                Mail::send('emails.payment-success', ['body' => [
                    'email' => $invoice->email,
                    'trip_name' => $invoice->trip_name,
                    'full_name' => $invoice->full_name,
                ]], function ($message) {
                    $message->to(Setting::get('email'));
                    $message->from(Setting::get('email'));
                    $message->subject('Trip Booking Payment');
                });
                return redirect('/');
            }
            throw new Error("Something went wrong. Please try again.");
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
