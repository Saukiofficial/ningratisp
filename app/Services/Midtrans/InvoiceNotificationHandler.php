<?php

namespace App\Services\Midtrans;

use App\Events\CustomerInvoicePaidEvent;
use App\Jobs\ActivateCustomerInternetJob;
use App\Jobs\SendWhatsappMessageJob;
use App\Models\VirtualAccount;
use App\Services\PaymentAutoService;
use App\Services\ReceivableService;
use Closure;
use Illuminate\Support\Facades\Pipeline;

class InvoiceNotificationHandler implements NotificationHandlerInterface
{
    public function handle(string $orderId, array $data): bool
    {
        $va = VirtualAccount::with('paymentMethod', 'invoice.customerPackage.customer')
            ->where('order_id', $orderId)->first();

        if (empty($va)) {
            return false;
        }

        $invoice = $va->invoice;
        $customer = $invoice->customerPackage->customer;

        $payment = app(PaymentAutoService::class)->recordCallbackIncomingPaymentWithAllocations(
            $customer,
            (float) ($data['gross_amount'] ?? 0),
            ($va->total_amount - $va->fee_amount ?? 0),
            $data['transaction_id'] ?? null,
            $va->paymentMethod,
            $invoice
        );

        if (! empty($payment)) {

            Pipeline::send($va)
                ->through([
                    function (VirtualAccount $va, Closure $next) use ($data) {
                        $va->status = $data['transaction_status'];
                        $va->save();

                        return $next($va);
                    },
                    // Dispatch Event for broadcasting
                    function (VirtualAccount $va, Closure $next) {
                        event(new CustomerInvoicePaidEvent($va));

                        return $next($va);
                    },
                    function (VirtualAccount $va, Closure $next) use ($data) {
                        ActivateCustomerInternetJob::dispatch($va->invoice->customerPackage->customer, $data);

                        return $next($va);
                    },
                    // TODO: Dispatch SendWhatsappMessageJob
                    // This job requires a Voucher model, but we only have a VirtualAccount for an Invoice.
                    // We need to determine how to get the relevant Voucher from the VA/Invoice context
                    // to dispatch SendWhatsappMessageJob::dispatch($voucher).

                ])
                ->then(function (VirtualAccount $va) {
                    // Pipeline finished
                    app(ReceivableService::class)->syncForInvoice($va->invoice);
                });
        }


        return ! empty($payment);
    }
}
