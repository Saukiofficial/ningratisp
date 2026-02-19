<?php

namespace App\Services;

use App\Jobs\ActivateCustomerInternetJob;
use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Payment;
use App\Models\PaymentAllocation;
use App\Models\PaymentMethod;
use App\Models\Voucher;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class PaymentService
{

    protected $isManualPayment = true;

    public function __construct(
        protected DiscountService $discountService
    ) {}

    public function recordIncomingVoucherPayment(Voucher $voucher, array $callbackData) {}

    public function recordIncomingPayment(
        Invoices $invoice,
        Customer $customer,
        float $amount,
        ?PaymentMethod $method = null,
        ?string $referenceId = null,
        ?string $filePath = null,
        ?string $fileName = null,
    ): Payment {

        // check fee
        if (! $method->fee) {
            throw new \Exception('Payment method does not have a fee');
        }

        return DB::transaction(function () use (

            $amount,
            $method,
            $referenceId,
            $invoice,
            $filePath,
            $fileName
        ) {
            $payment = new Payment;
            $payment->fill([
                'total_amount' => $amount,
                'price' => $amount,
                'reference_id' => $referenceId ?? ('PAY-' . now()->format('YmdHis')),
                'payment_datetime' => Carbon::now(),
                'is_cancel' => false,
                'description' => 'Incoming payment',
                'voucher_id' => null,
                'payment_method_id' => $method?->id,
                'payment_type' => 'incoming',
                'file_path' => $filePath,
                'file_name' => $fileName,
                'fee_id' => $method->fee->id,
                'is_manual' => $this->isManualPayment
                // Do not set invoice_id to allow allocations across multiple invoices
            ]);
            $payment->save();

            $this->payInvoice($payment, $invoice);

            return $payment;
        });
    }

    public function recordIncomingPaymentWithAllocations(
        Customer $customer,
        float $amount,
        ?PaymentMethod $method = null,
        ?string $referenceId = null,
        ?Invoices $invoice = null,
        ?string $filePath = null,
        ?string $fileName = null,
        array $invoiceIds = [],
        ?string $datetime = null
    ): Payment {

        // check fee
        if (! $method->fee) {
            throw new \Exception('Payment method does not have a fee');
        }

        return DB::transaction(function () use (
            $customer,
            $amount,
            $method,
            $referenceId,
            $invoice,
            $filePath,
            $fileName,
            $invoiceIds,
            $datetime
        ) {
            $payment = new Payment;
            $payment->fill([
                'total_amount' => $amount,
                'price' => $amount,
                'reference_id' => $referenceId ?? ('PAY-' . now()->format('YmdHis')),
                'payment_datetime' => !empty($datetime) ? Date::parse($datetime) : Carbon::now(),
                'is_cancel' => false,
                'description' => 'Incoming payment',
                'voucher_id' => null,
                'payment_method_id' => $method?->id,
                'payment_type' => 'incoming',
                'file_path' => $filePath,
                'file_name' => $fileName,
                'fee_id' => $method->fee->id,
                'is_manual' => $this->isManualPayment
                // Do not set invoice_id to allow allocations across multiple invoices
            ]);
            $payment->save();

            // disable isolir at
            $customer->isolir_at = null;
            $customer->save();

            if ($invoice) {
                // Allocate to the provided invoice up to its balance due
                $this->allocatePaymentToInvoices($payment, [
                    $invoice->id => $amount,
                ]);
            } else {
                // Auto allocate to customer's oldest open invoices
                $this->autoAllocate($payment, $customer, $invoiceIds);
            }

            // current payment
            if ($payment->payment_datetime->format('Y-m') == now()->format('Y-m')) {
                ActivateCustomerInternetJob::dispatch(
                    $customer,
                    Auth::user()
                );

                if (!empty($customer->isolir_at)) {
                    $customer->isolir_at = null;
                    $customer->save();
                }
            }

            return $payment;
        });
    }

    public function recordCallbackIncomingPaymentWithAllocations(
        Customer $customer,
        float $grossAmount,
        float $unitPrice,
        string $trxId,
        ?PaymentMethod $method = null,
        ?Invoices $invoice = null
    ): Payment {
        $payment = $this->recordIncomingPaymentWithAllocations(
            $customer,
            $grossAmount,
            $method,
            $trxId,
            $invoice
        );
        if (!empty($payment)) {
            $payment->update(['price' => $unitPrice]);
            $payment->save();
        }

        return $payment;
    }

    public function payInvoice(Payment $payment, Invoices $invoice): void
    {
        DB::transaction(function () use ($payment, $invoice) {
            $remaining = max(round((float) $payment->total_amount, 2), 0.0);
            if ($remaining <= 0) {
                return;
            }

            $invoice->refresh();
            $invoice->recalculateTotals();
            $invoice->save();
        });
    }

    public function allocatePaymentToInvoices(Payment $payment, array $invoiceIdsAndAmounts): void
    {
        DB::transaction(function () use ($payment, $invoiceIdsAndAmounts) {
            $allocatedSoFar = (float) $payment->allocations()->sum('amount');
            $remaining = max(round((float) $payment->total_amount - $allocatedSoFar, 2), 0.0);
            if ($remaining <= 0) {
                return;
            }

            foreach ($invoiceIdsAndAmounts as $invoiceId => $amount) {
                if ($remaining <= 0) {
                    break;
                }
                /** @var Invoices|null $invoice */
                $invoice = Invoices::query()->find($invoiceId);
                if (! $invoice) {
                    continue;
                }
                $invoice->refresh();
                $invoice->recalculateTotals();
                $invoice->save();

                $desired = (float) $amount;
                $toAllocate = min($desired, (float) ($invoice->balance_due ?? 0), $remaining);
                if ($toAllocate <= 0) {
                    continue;
                }

                $allocation = PaymentAllocation::query()->firstOrNew([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                ]);
                $current = (float) ($allocation->exists ? $allocation->amount : 0);
                $allocation->amount = round($current + $toAllocate, 2);
                $allocation->allocated_at = Carbon::now();
                $allocation->save();

                // Recompute invoice after allocation
                $invoice->recalculateTotals();
                $invoice->save();

                $this->deactivateDiscountIfUsed($invoice);

                $remaining = max(round($remaining - $toAllocate, 2), 0.0);
            }
        });
    }

    private function deactivateDiscountIfUsed(Invoices $invoice): void
    {
        if ($invoice->payment_status === Payment::STATUS_PAID && $invoice->discount_id) {
            $customer = $invoice->customerPackage->customer;
            $customer->discounts()
                ->wherePivot('discount_id', $invoice->discount_id)
                ->wherePivot('is_active', true)
                ->first()
                ?->pivot
                ->update(['is_active' => false]);
        }
    }

    public function autoAllocate(Payment $payment, Customer $customer, array $invoiceIds = []): void
    {
        DB::transaction(function () use ($payment, $customer, $invoiceIds) {
            $allocatedSoFar = (float) $payment->allocations()->sum('amount');
            $remaining = max(round((float) $payment->total_amount - $allocatedSoFar, 2), 0.0);
            if ($remaining <= 0) {
                return;
            }

            $invoices = $customer->invoices()
                ->where('invoices.status', Invoices::STATUS_UNPAID)
                ->where(function ($q) {
                    $q->where('balance_due', '>', 0)->orWhereNull('balance_due');
                });

            if (!empty($invoiceIds)) {
                $invoices->whereIn('invoices.id', $invoiceIds);
            }

            $invoices = $invoices->orderBy('due_date')
                ->orderBy('invoice_date')
                ->limit(100)
                ->get();

            foreach ($invoices as $invoice) {
                if ($remaining <= 0) {
                    break;
                }
                $invoice->refresh();
                $invoice->recalculateTotals();
                $invoice->save();

                $toAllocate = min((float) ($invoice->balance_due ?? 0), $remaining);
                if ($toAllocate <= 0) {
                    continue;
                }

                $allocation = PaymentAllocation::query()->firstOrNew([
                    'payment_id' => $payment->id,
                    'invoice_id' => $invoice->id,
                ]);
                $current = (float) ($allocation->exists ? $allocation->amount : 0);
                $allocation->amount = round($current + $toAllocate, 2);
                $allocation->allocated_at = Carbon::now();
                $allocation->save();

                $invoice->recalculateTotals();
                $invoice->save();

                $this->deactivateDiscountIfUsed($invoice);

                $remaining = max(round($remaining - $toAllocate, 2), 0.0);
            }

            // If any remaining, optionally leave as unallocated (could convert to customer credit via CreditService)
        });
    }

    public function handleRefund(Payment $payment, float $amount): Payment
    {
        return DB::transaction(function () use ($payment, $amount) {
            $refund = new Payment;
            // Attempt to associate refund to the same invoice if present, else to the first allocated invoice
            $invoiceId = $payment->invoice_id;
            if (! $invoiceId) {
                $firstAllocation = $payment->allocations()->orderBy('allocated_at')->first();
                $invoiceId = $firstAllocation?->invoice_id;
            }

            $refund->fill([
                'total_amount' => $amount,
                'reference_id' => 'RF-' . now()->format('YmdHis') . '-' . $payment->id,
                'payment_datetime' => Carbon::now(),
                'is_cancel' => false,
                'description' => 'Refund for payment #' . $payment->id,
                'voucher_id' => null,
                'payment_method_id' => $payment->payment_method_id,
                'payment_type' => 'refund',
                'invoice_id' => $invoiceId,
                'is_manual' => $this->isManualPayment
            ]);
            $refund->save();

            if ($invoiceId) {
                $invoice = Invoices::query()->find($invoiceId);
                if ($invoice) {
                    $invoice->recalculateTotals();
                    $invoice->save();
                }
            }

            return $refund;
        });
    }

    public function recomputeInvoicePayments(Invoices $invoice): void
    {
        $invoice->recalculateTotals();
        $invoice->save();
    }
}
