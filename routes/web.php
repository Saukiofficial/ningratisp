<?php

use App\Http\Controllers\HotspotController;
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

Route::post('confirm-payment', [HotspotController::class, 'confirmPayment'])->name('confirm-payment');
Route::post('create-invoice', [HotspotController::class, 'createInvoice'])->name('createinvoice');
Route::get('ipaymu/thanks', fn() => view('temp.thank-page'))->name('thanks-page');
Route::get('ipaymu/failed', fn() => view('temp.failed-page'))->name('failed-page');
