<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('https://www.poucesverts.ca', 302);
});


Route::resource('liste-attente', \App\Http\Controllers\WaitListController::class)->only([
    'create',
    'store'
]);


Route::get('paiement', [\App\Http\Controllers\RenewalController::class, 'start'])->name('renewal.start');
Route::post('paiement', [\App\Http\Controllers\RenewalController::class, 'handle'])->name('renewal.handler');
Route::get('paiement/verification', [\App\Http\Controllers\RenewalController::class, 'verifyAmounts'])->name('renewal.verify-amounts');
Route::get('paiement/confirmation', [\App\Http\Controllers\RenewalController::class, 'confirm'])->name('renewal.confirm');
Route::get('paiement/succes/{checkout_session_id}', [\App\Http\Controllers\RenewalController::class, 'success'])->name('renewal.success');
Route::get('paiement/cancel', [\App\Http\Controllers\RenewalController::class, 'cancel'])->name('renewal.cancel');

Route::get('demo', [\App\Http\Controllers\WaitListController::class, 'demo']);



Route::stripeWebhooks('accept-stripe-webhook');


Route::get('test', function() {
    if( class_exists('App\Jobs\StripeWebhooks\HandleCheckoutSessionComplete') ) {
        dd('Exists.');
    }

    if(class_exists('\App\Jobs\StripeWebhooks\HandleCheckoutSessionComplete')) {
        dd('Exists with leading slash.');
    }

    dd("Done.");

});
