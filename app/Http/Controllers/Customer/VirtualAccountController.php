<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Payment;
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
        } elseif (isset($response['status_code']) && $response['status_code'] == 412) {
            $virtualAccount->status = VirtualAccount::STATUS_EXPIRE;
            $virtualAccount->save();

            return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Pembayaran telah kadaluarsa.');
        }

        return back()->with('error', 'Gagal membatalkan pembayaran.');
    }

    public function checkStatus(VirtualAccount $virtualAccount, MidtransService $midtransService)
    {
        $response = $midtransService->getStatusVirtualAccount($virtualAccount->transaction_id);

        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $virtualAccount->status = $response['transaction_status'];
            $virtualAccount->save();

            if (!in_array($virtualAccount->status, [VirtualAccount::STATUS_PENDING, VirtualAccount::STATUS_SETTLEMENT])) {
                return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Status pembayaran berhasil diperbarui.');
            } elseif ($virtualAccount->status == VirtualAccount::STATUS_SETTLEMENT) {
                $this->handlePaymentFromCheckStatus($virtualAccount, $response);
                return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Pembayaran berhasil dilakukan.');
            }
        } elseif (isset($response['status_code']) && $response['status_code'] == 407) {
            $virtualAccount->status = VirtualAccount::STATUS_EXPIRE;
            $virtualAccount->save();

            return to_route('invoices.show', $virtualAccount->invoice_id)->with('success', 'Pembayaran telah kadaluarsa.');
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    private function handlePaymentFromCheckStatus(VirtualAccount $va, $data): ?Payment
    {
        $invoice = $va->invoice;
        $customer = $invoice->customerPackage->customer;

        $payment = app(\App\Services\PaymentService::class)->recordCallbackIncomingPaymentWithAllocations(
            $customer,
            (float) ($data['gross_amount'] ?? 0),
            ($va->total_amount - $va->fee_amount ?? 0),
            $data['transaction_id'] ?? null,
            $va->paymentMethod,
            $invoice
        );

        app(\App\Services\ReceivableService::class)->syncForInvoice($invoice);

        return $payment;
    }
}
