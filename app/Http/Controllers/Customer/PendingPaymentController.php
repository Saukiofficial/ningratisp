<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PendingPaymentController extends Controller
{
    public function show(Invoices $invoice)
    {
        return Inertia::render('Customer/PendingPayment', [
            'invoice' => $invoice,
        ]);
    }
}