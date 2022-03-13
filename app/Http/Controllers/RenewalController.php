<?php

namespace App\Http\Controllers;

use App\Member;
use Exception;
use Illuminate\Http\Request;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class RenewalController extends Controller
{
    public function start() {
        return view('renewal.start');
    }

    public function handle(Request $request) {

        if($request->has('email')) {
            return $this->findGardener($request->get('email'));
        } else if($request->has('small_gardens')) {
            return $this->calculateCharges($request);
        } else if($request->has('confirm')) {
            return $this->sendToStripe($request);
        }

        return redirect(route('renewal.start'));
    }

    protected function sendToStripe(Request $request) {
        if(!$request->has('confirm') || $request->get('confirm') !== 'yes') {
            return redirect(route('renewal.start'));
        }

        $member = session()->get('member');
        $to_charge = session()->get('to_charge');
        if(empty($member) || empty($to_charge)) {
            return redirect(route('renewal.start'));
        }

        $line_items = [];
        if($to_charge['small']['quantity'] > 0) {
            $line_items[] = [
                'price' => config('services.stripe.small_garden_product_id'),
                'quantity' => $to_charge['small']['quantity'],
            ];
        }

        if($to_charge['large']['quantity'] > 0) {
            $line_items[] = [
                'price' => config('services.stripe.large_garden_product_id'),
                'quantity' => $to_charge['large']['quantity'],
            ];
        }

        $checkout_data = [
            'client_reference_id' => sprintf('%s - %s', date('Y'), session()->get('member')->get('Adresse Courriel')),
            'customer_email' => session()->get('member')->get('Adresse Courriel'),
            'line_items' => [$line_items],
            'mode' => 'payment',
            'success_url' => url('paiement/succes/{CHECKOUT_SESSION_ID}'),
            'cancel_url' => url(route('renewal.cancel')),
        ];

        if(isset($to_charge['discount'])) {
            $checkout_data['discounts'] = [[
                'coupon' => config('services.stripe.biblio_loisir_coupon_id')
            ]];
        }

        Stripe::setApiKey(config('services.stripe.secret_key'));
        $session = Session::create($checkout_data);

        return redirect($session->url);

    }

    protected function calculateCharges(Request $request) {
        // Verify!
        $validated = $request->validate([
            'small_gardens' => 'required|int|max:4',
            'large_gardens' => 'required|int|max:2',
        ]);

        $member = session()->get('member');
        if(empty($member)) {
            return redirect(route('renewal.start'));
        }

        if($validated['small_gardens'] >=3 ) {
            $validated['large_gardens'] = $validated['large_gardens'] + 1;
            $validated['small_gardens'] = $validated['small_gardens'] - 3;
        }

        $to_charge = [
            'large' => [
                'quantity' => $validated['large_gardens'],
                'subtotal' => ($validated['large_gardens'] * 30),
            ],
            'small' => [
                'quantity' => $validated['small_gardens'],
                'subtotal' => ($validated['small_gardens'] * 14)
            ]
        ];


        $to_charge['total'] = $to_charge['large']['subtotal'] + $to_charge['small']['subtotal'];

        if($member->has_biblio_loisir() && $to_charge['total'] >= 2) {
            $to_charge['discount'] = 2;
            $to_charge['total'] = $to_charge['total'] - 2;
        }
        session()->put('to_charge', $to_charge);

        return redirect(route('renewal.confirm'));
    }

    public function confirm() {
        $member = session()->get('member');
        $to_charge = session()->get('to_charge');
        if(empty($member) || empty($to_charge)) {
            return redirect(route('renewal.start'));
        }

        return view('renewal.confirm', [
            'small_gardens' => $member->get('PETIT JARDIN (inscrire tous les numéros)'),
            'large_gardens' => $member->get('GRAND JARDIN (inscrire tous les numéros)'),
            'to_charge' => $to_charge
        ]);
    }

    protected function findGardener(string $email) {

        // Try to get the email.
        try {
            $member = new Member();
            $member->load_from_email($email);
            if($member->has_paid()) {
                return back()->with('status', 'paid');
            }
        } catch (Exception $e) {
            return back()->with('status', 'not found');
        }

        session()->put('member', $member);

        return redirect(route('renewal.verify-amounts'));
    }

    public function verifyAmounts() {

        $member = session()->get('member');

        if(empty($member)) {
            return redirect(route('renewal.start'));
        }

        // how many small gardens?
        $small_gardens = $member->get('PETIT JARDIN (inscrire tous les numéros)');
        $large_gardens = $member->get('GRAND JARDIN (inscrire tous les numéros)');

        $small_gardens_count = count(explode(',', $small_gardens));
        $large_gardens_count = count(explode(',', $large_gardens));

        return view('renewal.verify-details', [
            'small_gardens' => $small_gardens,
            'large_gardens' => $large_gardens,
            'small_gardens_count' => $small_gardens_count,
            'large_gardens_count' => $large_gardens_count,
            'has_biblio_loisir' => $member->has_biblio_loisir(),
        ]);
    }

    public function success(string $checkout_session_id, Request $request) {
        return view('renewal.success');
    }

    public function cancel(Request $request) {
        return view('renewal.cancel');
    }
}
