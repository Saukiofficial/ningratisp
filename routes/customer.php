<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\PendingPaymentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register customer routes for your application.
|
*/

Route::prefix('customer')->group(function () {

    Route::get('/', fn() => to_route('login'));

    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'authProcess'])->name('login.auth');


    // test view
    Route::group(['middleware' => 'auth:customers'], function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/invoices/{invoice}/checkout', [InvoiceController::class, 'checkout'])->name('invoices.checkout');
        Route::post('/invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::get('/pending-payment/{virtualAccount}', [PendingPaymentController::class, 'show'])->name('pending-payment.show');
        Route::resource('/invoices', InvoiceController::class);

        Route::post('/logout', [AuthController::class, 'logOut'])->name('logout');
    });
});
