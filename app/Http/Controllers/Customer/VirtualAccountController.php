<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\VirtualAccount;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class VirtualAccountController extends Controller
{
    public function cancel(VirtualAccount $virtualAccount, MidtransService $midtransService)
    {
        $response = $midtransService->cancelVirtualAccount($virtualAccount->transaction_id, $virtualAccount->invoice->invoice_number);

        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $virtualAccount->status = VirtualAccount::STATUS_CANCEL;
            $virtualAccount->save();

            return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Pembayaran berhasil dibatalkan.');
        }

        return back()->with('error', 'Gagal membatalkan pembayaran.');
    }

    public function checkStatus(VirtualAccount $virtualAccount, MidtransService $midtransService)
    {
        $response = $midtransService->getStatusVirtualAccount($virtualAccount->transaction_id);

        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $virtualAccount->status = $response['transaction_status'];
            $virtualAccount->save();

            if ($virtualAccount->status !== 'pending') {
                return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Status pembayaran berhasil diperbarui.');
            }
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
