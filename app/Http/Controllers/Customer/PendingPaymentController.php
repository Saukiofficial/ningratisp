<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\VirtualAccount;
use App\Services\Customer\VirtualAccountStatusServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class PendingPaymentController extends Controller
{
    public function __invoke(VirtualAccount $virtualAccount, VirtualAccountStatusServices $vaStatusService)
    {
        $this->authorize('view', $virtualAccount);

        $result = $vaStatusService->syncStatus($virtualAccount);
        if ($result['status'] !== 'unchanged') {
            return to_route('customer.invoices.show', $result['virtualAccount']->invoice_id)
                ->with('success', $result['message']);
        }

        $virtualAccount->load(['invoice.items', 'paymentMethod']);

        return Inertia::render('Customer/PendingPayment', [
            'virtualAccount' => $virtualAccount,
            'flash' => $this->getFlash()
        ]);
    }
}
