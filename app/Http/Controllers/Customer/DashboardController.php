<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Invoices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        $pendingInvoice = $user->invoices()->pending()->first();
        if ($pendingInvoice) {
            return to_route('pending-payment.show', $pendingInvoice);
        }

        $customers = [
            'id' => 1,
            'nama' => $user->username,
            'kode_unik' => $user->billing_number,
        ];

        $unpaidInvoices = $user->invoices()
            ->where('invoices.status', Invoices::STATUS_UNPAID)
            ->latest('invoice_date')
            ->get();

        return Inertia::render('Customer/Dashboard', [
            'pelanggan' => $customers,
            'statusLangganan' => $unpaidInvoices->isEmpty(),
            'unpaid_invoices' => $unpaidInvoices,
            'pending_invoice' => $pendingInvoice,
        ]);
    }
}
