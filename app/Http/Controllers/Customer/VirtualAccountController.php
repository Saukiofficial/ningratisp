<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\VirtualAccount;
use App\Services\Customer\VirtualAccountStatusServices;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function cancel(VirtualAccount $virtualAccount, VirtualAccountStatusServices $statusService)
    {
        $result = $statusService->cancel($virtualAccount);

        if ($result['status'] === 'failed') {
            return back()->with('error', $result['message']);
        }

        return to_route('customer.invoices.show', $result['virtualAccount']->invoice_id)
            ->with('success', $result['message']);
    }

    public function checkStatus(VirtualAccount $virtualAccount, VirtualAccountStatusServices $statusService)
    {
        $result = $statusService->syncStatus($virtualAccount);

        if ($result['status'] === 'failed') {
            return back()->with('error', $result['message']);
        }

        if ($result['status'] === 'unchanged') {
            return back()->with('success', $result['message']);
        }

        return to_route('customer.invoices.show', $result['virtualAccount']->invoice_id)
            ->with('success', $result['message']);
    }
}
