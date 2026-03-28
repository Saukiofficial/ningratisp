<?php

namespace App\Services\Customer;

use App\Models\Payment;
use App\Models\VirtualAccount;
use App\Services\MidtransService;
use App\Services\PaymentAutoService;
use App\Services\ReceivableService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VirtualAccountStatusServices
{
    public function __construct(
        protected MidtransService $midtransService,
        protected PaymentAutoService $paymentAutoService,
        protected ReceivableService $receivableService,
    ) {}

    /**
     * Sync VA status from Midtrans.
     *
     * @return array{
     *   status: 'updated'|'settled'|'expired'|'unchanged'|'failed',
     *   virtualAccount: VirtualAccount,
     *   payment: Payment|null,
     *   message: string,
     * }
     */
    public function syncStatus(VirtualAccount $virtualAccount): array
    {
        $response = $this->midtransService->getStatusVirtualAccount($virtualAccount->transaction_id);

        $statusCode = $response['status_code'] ?? null;

        return match (true) {
            in_array($statusCode, [200, 201])  => $this->handleSyncSuccess($virtualAccount, $response),
            $statusCode == 407 => $this->handleExpired($virtualAccount),
            default            => $this->logAndFail($virtualAccount, $response, 'syncStatus'),
        };
    }

    /**
     * Cancel a VA via Midtrans.
     *
     * @return array{
     *   status: 'cancelled'|'expired'|'failed',
     *   virtualAccount: VirtualAccount,
     *   payment: null,
     *   message: string,
     * }
     */
    public function cancel(VirtualAccount $virtualAccount): array
    {
        $response = $this->midtransService->cancelVirtualAccount(
            $virtualAccount->transaction_id,
            $virtualAccount->invoice->invoice_number,
        );

        $statusCode = $response['status_code'] ?? null;

        return match (true) {
            $statusCode == 200 => $this->handleCancelled($virtualAccount),
            $statusCode == 412 => $this->handleExpired($virtualAccount),
            default            => $this->logAndFail($virtualAccount, $response, 'cancel'),
        };
    }

    // -------------------------------------------------------------------------
    // Status handlers
    // -------------------------------------------------------------------------

    private function handleSyncSuccess(VirtualAccount $virtualAccount, array $response): array
    {
        $newStatus = $response['transaction_status'];

        $virtualAccount->status = $newStatus;
        $virtualAccount->save();

        if ($newStatus === VirtualAccount::STATUS_SETTLEMENT) {
            $payment = $this->recordPayment($virtualAccount, $response);

            return $this->result('settled', $virtualAccount, $payment, 'Pembayaran berhasil dilakukan.');
        }

        if ($newStatus !== VirtualAccount::STATUS_PENDING) {
            return $this->result('updated', $virtualAccount, null, 'Status pembayaran berhasil diperbarui.');
        }

        return $this->result('unchanged', $virtualAccount, null, 'Status pembayaran masih menunggu.');
    }

    private function handleCancelled(VirtualAccount $virtualAccount): array
    {
        $virtualAccount->status = VirtualAccount::STATUS_CANCEL;
        $virtualAccount->save();

        return $this->result('cancelled', $virtualAccount, null, 'Pembayaran berhasil dibatalkan.');
    }

    private function handleExpired(VirtualAccount $virtualAccount): array
    {
        $virtualAccount->status = VirtualAccount::STATUS_EXPIRE;
        $virtualAccount->save();

        return $this->result('expired', $virtualAccount, null, 'Pembayaran telah kadaluarsa.');
    }

    // -------------------------------------------------------------------------
    // Payment recording
    // -------------------------------------------------------------------------

    private function recordPayment(VirtualAccount $va, array $data): ?Payment
    {
        return DB::transaction(function () use ($va, $data) {
            $invoice  = $va->invoice;
            $customer = $invoice->customerPackage->customer;

            $payment = $this->paymentAutoService->recordCallbackIncomingPaymentWithAllocations(
                $customer,
                (float) ($data['gross_amount'] ?? 0),
                ($va->total_amount - $va->fee_amount ?? 0),
                $data['transaction_id'] ?? null,
                $va->paymentMethod,
                $invoice,
            );

            $this->receivableService->syncForInvoice($invoice);

            return $payment;
        });
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function logAndFail(VirtualAccount $virtualAccount, array $response, string $action): array
    {
        Log::warning("VirtualAccountStatusService::{$action} unexpected response", [
            'transaction_id' => $virtualAccount->transaction_id,
            'response'       => $response,
        ]);

        return $this->result('failed', $virtualAccount, null, 'Gagal memproses pembayaran.');
    }

    private function result(
        string $status,
        VirtualAccount $virtualAccount,
        ?Payment $payment,
        string $message,
    ): array {
        return [
            'status'         => $status,
            'virtualAccount' => $virtualAccount,
            'payment'        => $payment,
            'message'        => $message,
        ];
    }
}
