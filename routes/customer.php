<?php

use App\Http\Controllers\Customer\AuthController;
use App\Http\Controllers\Customer\ComplaintController;
use App\Http\Controllers\Customer\DashboardController;
use App\Http\Controllers\Customer\DiscountController;
use App\Http\Controllers\Customer\InvoiceController;
use App\Http\Controllers\Customer\PendingPaymentController;
use App\Http\Controllers\Customer\VirtualAccountController;
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
    Route::group(['middleware' => 'auth:customers', 'as' => 'customer.'], function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::get('/invoices/{invoice}/checkout', [InvoiceController::class, 'checkout'])->name('invoices.checkout');
        Route::post('invoices/{invoice}/pay', [InvoiceController::class, 'pay'])->name('invoices.pay');
        Route::post('invoices/{invoice}/discount', [InvoiceController::class, 'applyDiscount'])->name('invoices.apply-discount');
        Route::post('invoices/{invoice}/discount/remove', [InvoiceController::class, 'removeDiscount'])->name('invoices.remove-discount');
        Route::post('discounts/claim', [DiscountController::class, 'claim'])->name('discounts.claim')
            ->middleware('throttle:customer_claim_discount');

        Route::resource('complaints', ComplaintController::class)->only(['index', 'store']);

        Route::get('pending-payment/{virtualAccount}', PendingPaymentController::class)->name('pending-payment.show');
        Route::post('/virtual-accounts/{virtualAccount}/cancel', [VirtualAccountController::class, 'cancel'])->name('virtual-accounts.cancel');
        Route::post('/virtual-accounts/{virtualAccount}/check-status', [VirtualAccountController::class, 'checkStatus'])->name('virtual-accounts.check-status');
        Route::resource('/invoices', InvoiceController::class);

        Route::post('/logout', [AuthController::class, 'logOut'])->name('logout');
    });
});
