<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PendingPaymentController extends Controller
{
    public function show(VirtualAccount $virtualAccount)
    {
        if ($virtualAccount->status != VirtualAccount::STATUS_PENDING) {
            return to_route('invoices.index');
        }

        $virtualAccount->load(['invoice.items', 'paymentMethod']);

        return Inertia::render('Customer/PendingPayment', [
            'virtualAccount' => $virtualAccount,
        ]);
    }
}
