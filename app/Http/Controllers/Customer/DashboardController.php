<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Customer\Invoices;
use App\Models\VirtualAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var \App\Models\Customer */
        $user = auth('customers')->user();
        $customers = [
            'id' => 1,
            'nama' => $user->full_name ?? $user->billing_number,
            // 'username' => $user->username,
            'kode_unik' => $user->billing_number,
            'package' => $user->activePackage?->package ?? null
        ];

        $unpaidInvoices = $user->invoices()
            ->where('invoices.status', Invoices::STATUS_UNPAID)
            ->latest('invoice_date')
            ->get();

        return Inertia::render('Customer/Dashboard', [
            'pelanggan' => $customers,
            'statusLangganan' => $unpaidInvoices->isEmpty(),
            'unpaid_invoices' => $unpaidInvoices,
            'pending_va' => $request->attributes->get('pending_va'),
            'isolir_at' => $user->isolir_at ? Date::parse($user->isolir_at)->format('d F Y, H:i:s') : null
        ]);
    }
}
