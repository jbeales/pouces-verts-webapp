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

        $this->firstSheet()->append([[
            'Timestamp' => Carbon::now()->format('j F Y'),
            'Description' => $event['client_reference_id'],
            'Amount' => (string)$event['amount_total']/100,
            'Stripe ID' => $event['payment_intent'],
        ]]);

    }
}
