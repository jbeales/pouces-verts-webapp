<?php

use App\Member;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('test:sheet', function() {
    $member = new Member();
    $member->load_from_email('john@johnbeales.com');
    dd($member);
});

Artisan::command('test:jobclass', function() {
   $this->line(print_r(class_exists('App\Jobs\StripeWebhooks\HandleCheckoutSessionComplete'), true));
   $this->line(print_r(class_exists('\App\Jobs\StripeWebhooks\HandleCheckoutSessionComplete'), true));
});
