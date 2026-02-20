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
            'nama' => $user->full_name ?? $user->username,
            'username' => $user->username,
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
            'pending_va' => $pendingVA,
            'isolir_at' => $user->isolir_at ? Date::parse($user->isolir_at)->format('d F Y, H:i:s') : null
        ]);
    }
}
