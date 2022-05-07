<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function start() {
        return view('payment.start');
    }


    public function handle(Request $request) {

        $validated = $request->validate([
            'amount' => 'required|regex:/^[0-9]{1,4}[.,]?([0-9]{0,2})?$/',
            'email' => 'required|email',
            'description' => 'nullable|string',
        ]);

        $amount = $validated['amount'];

        session([
            'amount' => $amount,
            'description' => $validated['description'] ?? '',
            'email' => $validated['email']
        ]);


        $amount = str_replace(',', '.', $amount);
        $amount = $amount * 100;

        $description = trim( sprintf('%s - %s', $validated['email'], $validated['description']), '- ');

        $checkout_data = [
            'client_reference_id' => $description,
            'customer_email' => $validated['email'],
            'line_items' => [
                [
                    'price_data' => [
                        'product_data' => [
                            'name' => 'Paiement / Payment',
                            'description' => $validated['description'],
                        ],
                        'unit_amount' => $amount,
                        'currency' => 'CAD',
                    ],
                    'quantity' => 1,
                ]
            ],
            'mode' => 'payment',
            'success_url' => url('paiement/succes/{CHECKOUT_SESSION_ID}'),
            'cancel_url' => url(route('payment.cancel')),
        ];



        Stripe::setApiKey(config('services.stripe.secret_key'));
        $session = Session::create($checkout_data);

        return redirect($session->url);

    }


    public function success(string $checkout_session_id, Request $request) {
        Stripe::setApiKey(config('services.stripe.secret_key'));
        $session = Session::retrieve($checkout_session_id);
        return view('payment.success', ['stripe_session' => $session]);
    }

    public function cancel(Request $request) {
        return view('payment.cancel');
    }
}
