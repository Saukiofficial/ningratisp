<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();

        $pendingVA = VirtualAccount::whereHas('invoice.customerPackage.customer', function ($query) use ($user) {
            $query->where('id', $user->id);
        })->where('status', 'pending')->where('expired_at', '>', now())->first();

        if ($pendingVA) {
            return to_route('pending-payment.show', $pendingVA);
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
            'pending_va' => $pendingVA,
        ]);
    }
}
