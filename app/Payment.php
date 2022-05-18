<?php

namespace App;

use App\Concerns\InteractsWithGoogleSheets;
use Carbon\Carbon;

class Payment {
    use InteractsWithGoogleSheets;

    public function __construct() {
        $this->bootSheets([[
            'spreadsheet_id' => config('google.sheets.payments.spreadsheet_id'),
            'range_id' => config('google.sheets.payments.range_id')
        ]]);
    }

    public static function instance() {
        static $payment;
        if(empty($payment)) {
            $payment = new static;
        }
        return $payment;
    }




    public function record( array $event ) {

        // Get the description of what was paid for.
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret_key'));
        $session = $stripe->checkout->sessions->retrieve(
            $event['id'],
            [
                'expand' => ['line_items.data.price.product']
            ]
        );
        $product = $session->line_items->first()->price->product;



        $this->firstSheet()->append([[
            'Timestamp' => Carbon::now()->format('j F Y H:i:s e'),
            'Charge Name' => $event['client_reference_id'],
            'Product' => $product->name,
            'Description' => $product->description,
            'Amount' => (string)$event['amount_total']/100,
            'Stripe ID' => $event['payment_intent'],
        ]]);

    }
}
