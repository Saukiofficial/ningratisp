<?php

use App\Helpers\MikrotikAPI;
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
    Route::get('voucher', [HotspotController::class, 'preVoucherRequest']);
    Route::post('requestvoucher', [HotspotController::class, 'voucherRequest'])->name('voucherRequest');
    Route::post('requestvoucherqris', [HotspotController::class, 'voucherRequestQris'])->name('voucherRequest-qris');
    Route::post('callback', [HotspotController::class, 'midtransCallback']);
    // Route::post('paymentstate', [HotspotController::class, '']);
});
Route::get('voucherdetails/{sealcode?}', [HotspotController::class, 'getVoucherDetails'])->name('voucherDetails');
