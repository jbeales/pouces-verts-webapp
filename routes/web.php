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
    return view('welcome');
});


Route::resource('liste-attente', \App\Http\Controllers\WaitListController::class)->only([
    'create',
    'store'
]);

Route::get('demo', [\App\Http\Controllers\WaitListController::class, 'demo']);
