<?php

use App\Http\Controllers\HotspotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['prefix' => 'midtrans'], function () {
    Route::post('voucher', [HotspotController::class, 'preVoucherRequest']);
    Route::post('requestvoucher', [HotspotController::class, 'voucherRequest'])->name('postVoucher');
    Route::post('callback', [HotspotController::class, 'midtransCallback']);
});
