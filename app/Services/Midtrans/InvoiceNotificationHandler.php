<?php

namespace App\Services\Midtrans;

use App\Events\CustomerInvoicePaidEvent;
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
        if (!empty($payment)) {
            $va->status = $data['transaction_status'];
            $va->save();

            event(new CustomerInvoicePaidEvent($va));
        }
        app(\App\Services\ReceivableService::class)->syncForInvoice($invoice);

        return !empty($payment);
    }
}
