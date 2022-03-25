<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'secret_key' => env('STRIPE_SECRET_KEY'),
        'small_garden_product_id' => env('STRIPE_SMALL_GARDEN_PRODUCT_ID'),
        'large_garden_product_id' => env('STRIPE_LARGE_GARDEN_PRODUCT_ID'),
        'biblio_loisir_coupon_id' => env('STRIPE_BIBLIO_LOISIR_COUPON_CODE')
    ],

    'twilio' => [
        'number' => env( 'TWILIO_PHONE_NUMBER' ),
        'sid'    => env( 'TWILIO_SID' ),
        'token'  => env( 'TWILIO_TOKEN' ),
    ],

];
