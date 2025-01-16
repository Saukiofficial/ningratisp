<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('index');
})->name('index');

Route::get('/service-details', function () {
    return view('service-details');
})->name('service-details');

Route::get('midtrans/paymentstate', fn() => view('hotspot/success-payment-landing'));
Route::group(['prefix' => 'midtrans/payment'], function () {
    Route::get('success', fn() => view('temp.thank-page'));
    Route::get('failed', fn() => view('temp.failed-page'));
});
