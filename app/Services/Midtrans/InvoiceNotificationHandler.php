<?php

namespace App\Services\Midtrans;

use App\Models\Invoices;
use App\Models\VirtualAccount;
use App\Services\InvoiceService;
use App\Services\PaymentService;

class InvoiceNotificationHandler implements NotificationHandlerInterface
{
    public function handle(string $orderId, array $data): bool
    {
        $va = VirtualAccount::with('paymentMethod', 'invoice.customerPackage.customer',)
            ->where('order_id', $orderId)->first();

        if (empty($va)) {
            return false;
        }

        $payment = app(\App\Services\PaymentService::class)->recordIncomingPaymentWithAllocations(
            $va->invoice->customerPackage->customer,
            (float) ($data['amount'] ?? 0),
            $va->paymentMethod,
            $data['reference_id'] ?? null,
            $va->invoice,
            $data['file_path'],
            $data['file_name']
        );

        return !empty($payment);
    }
}
