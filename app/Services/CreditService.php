<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerCredit;
use App\Models\Invoices;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CreditService
{
    public function getBalance(Customer $customer): float
    {
        $credits = (float) CustomerCredit::query()->where('customer_id', $customer->id)->where('type', 'credit')->sum('amount');
        $debits = (float) CustomerCredit::query()->where('customer_id', $customer->id)->where('type', 'debit')->sum('amount');
        return round($credits - $debits, 2);
    }

    public function addCredit(Customer $customer, float $amount, ?string $description = null, ?string $referenceId = null): CustomerCredit
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Credit amount must be greater than zero.');
        }

        return CustomerCredit::create([
            'customer_id' => $customer->id,
            'type' => 'credit',
            'amount' => $amount,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);
    }

    public function addDebit(Customer $customer, float $amount, ?string $description = null, ?string $referenceId = null): CustomerCredit
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Debit amount must be greater than zero.');
        }

        return CustomerCredit::create([
            'customer_id' => $customer->id,
            'type' => 'debit',
            'amount' => $amount,
            'reference_id' => $referenceId,
            'description' => $description,
        ]);
    }

    public function applyCreditToInvoice(Customer $customer, Invoices $invoice, float $amount): array
    {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be greater than zero.');
        }

        $available = $this->getBalance($customer);
        if ($available <= 0) {
            return ['applied' => 0.0, 'remaining_credit' => 0.0, 'payment' => null];
        }

        $invoice->refresh();
        $invoice->recalculateTotals();
        $invoice->save();

        $toApply = min($amount, $available, (float) ($invoice->balance_due ?? 0));
        if ($toApply <= 0) {
            return ['applied' => 0.0, 'remaining_credit' => $available, 'payment' => null];
        }

        return DB::transaction(function () use ($customer, $invoice, $toApply, $available) {
            $payment = new Payment();
            $payment->fill([
                'total_amount' => $toApply,
                'reference_id' => 'CR-APPLY-' . now()->format('YmdHis'),
                'payment_datetime' => Carbon::now(),
                'is_cancel' => false,
                'description' => 'Apply customer credit to invoice ' . ($invoice->invoice_number ?? $invoice->id),
                'voucher_id' => null,
                'payment_method_id' => null,
                'payment_type' => 'incoming',
                'invoice_id' => $invoice->id,
            ]);
            $payment->save();

            // Record a debit on the customer's credit ledger for the applied amount
            CustomerCredit::create([
                'customer_id' => $customer->id,
                'type' => 'debit',
                'amount' => $toApply,
                'reference_id' => 'PAY:' . $payment->id,
                'description' => 'Credit applied to invoice ' . ($invoice->invoice_number ?? $invoice->id),
            ]);

            // Update invoice totals
            $invoice->recalculateTotals();
            $invoice->save();

            return [
                'applied' => $toApply,
                'remaining_credit' => round($available - $toApply, 2),
                'payment' => $payment,
            ];
        });
    }
}
