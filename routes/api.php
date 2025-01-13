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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'ipaymu'], function () {
    Route::post('callback', [HotspotController::class, 'ipaymuCallback'])->name('ipaymu-callback');
    Route::get('infolog/{orderid?}', [HotspotController::class, 'ipaymuInfoLog'])->name('ipaymu-log');
});
