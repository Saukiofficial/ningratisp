<?php

namespace App\Services;

use App\Models\CustomerPackages;
use App\Models\InvoiceItem;
use App\Models\Invoices;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    private bool $hasAdjustment = false;

    public function __construct(
        protected DiscountService $discountService
    ) {}

    public function generateMonthlyInvoicesForDate(Carbon $date): int
    {
        $periodStart = $date->copy()->startOfMonth();
        $periodEnd = $date->copy()->endOfMonth();

        $packages = CustomerPackages::query()
            ->with(['package', 'customer'])
            ->where('status', CustomerPackages::STATUS_ACTIVE)
            ->whereDate('start_date', '<=', $periodEnd->toDateString())
            ->where(function ($q) use ($periodStart) {
                $q->whereNull('end_date')->orWhereDate('end_date', '>=', $periodStart->toDateString());
            })
            ->get();

        $created = 0;

        foreach ($packages as $cp) {
            $exists = Invoices::query()
                ->whereHas('customerPackage.customer', function (Builder $query) use ($cp) {
                    return $query->where('id', $cp->customer_id);
                })
                // ->where('customer_package_id', $cp->id)
                ->whereYear('period_start', $periodStart->format('Y'))
                ->whereMonth('period_start', $periodStart->format('m'))
                ->where('status', '!=', Invoices::STATUS_CANCELLED)
                ->exists();

            if ($exists) {
                continue;
            }

            // skip if has active invoice (except manual invoice) with isolir
            $customer = $cp->customer;
            $activeInvoice = $customer->invoices()
                ->where('invoices.status', Invoices::STATUS_UNPAID)
                ->first();

            if ($activeInvoice && !empty($customer->isolir_at)) {
                continue;
            }

            $invoice = $this->generateInvoiceForCustomerPackage(
                $cp,
                $periodStart,
                $periodEnd,
                $date
            );
            if ($invoice && $invoice->exists) {
                $created++;
            }
        }

        return $created;
    }

    public function generateInvoiceForCustomerPackage(CustomerPackages $cp, Carbon $periodStart, Carbon $periodEnd, ?Carbon $date = null): Invoices
    {
        // Create invoice shell
        $invoice_date = $date?->toDateString() ?? Carbon::now()->toDateString();
        $due_date = $date?->copy()->addDays(10)->toDateString() ?? Carbon::now()->copy()->addDays(30)->toDateString();
        $invoice = new Invoices;
        $invoice->fill([
            'invoice_number' => $this->makeInvoiceNumber($cp, $periodStart),
            'customer_package_id' => $cp->id,
            'invoice_date' => $invoice_date,
            'due_date' => $due_date,
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),
            'status' => Invoices::STATUS_UNPAID,
            'invoice_type' => Invoices::TYPE_MONTHLY,
            'amount' => $cp->package->price,
        ]);
        $invoice->amount = $this->generateRemainingAmount($invoice);
        $invoice->save();

        // Add main charge line from package price
        $pkg = $cp->package;
        $price = $pkg ? (float) $pkg->price : 0.0;
        $desc = $pkg ? ("Langganan Bulanan: {$pkg->name}") : 'Langganan Bulanan';
        $this->addItem($invoice, InvoiceItem::ITEM_CHARGE, $desc, 1, $price);

        // add adjustment charge
        if ($this->hasAdjustment) {
            $this->addItem(
                $invoice,
                InvoiceItem::ITEM_ADJUSTMENT,
                'Penyesuaian (' . $desc . ')',
                1,
                - ($price - $invoice->amount)
            );
            $this->hasAdjustment = false;
        }

        // Recalculate totals and persist
        $invoice->recalculateTotals();
        $invoice->save();

        // Apply discounts if applicable
        $this->discountService->calculateInvoiceDiscount($invoice);

        return $invoice;
    }

    public function addItem(Invoices $invoice, string $type, string $description, float $qty, float $unitPrice): void
    {
        $invoice->items()->create([
            'item_type' => $type,
            'description' => $description,
            'quantity' => $qty,
            'unit_price' => $unitPrice,
        ]);
    }

    public function recalc(Invoices $invoice): void
    {
        $invoice->recalculateTotals();
    }

    public function updatePaymentStatus(Invoices $invoice): void
    {
        $invoice->recalculateTotals();
        $invoice->save();
    }

    public function voidInvoice(Invoices $invoice): void
    {
        // Placeholder: mark invoice as cancelled and handle reversals if any
    }

    public function createManualInvoicesForPackage(int $customerPackageId, string $dueDate, int $batch = 1): void
    {
        DB::transaction(function () use ($customerPackageId, $dueDate, $batch) {
            $customerPackage = CustomerPackages::with(['package', 'customer'])->find($customerPackageId);
            if (! $customerPackage) {
                throw new \Exception('Selected package not found.');
            }

            $dueDate = Carbon::parse($dueDate);

            for ($i = 0; $i < $batch; $i++) {
                $invoiceDueDate = $i > 0 ? $dueDate->copy()->addMonths($i) : $dueDate;
                $this->generateManualInvoiceForPackage($customerPackage, $invoiceDueDate);
            }
        });
    }

    private function generateManualInvoiceForPackage(CustomerPackages $cp, Carbon $dueDate): Invoices
    {
        $periodStart = $dueDate->copy()->startOfMonth();
        $periodEnd = $dueDate->copy()->endOfMonth();

        // Create invoice shell
        $invoice = new Invoices;
        $invoice->fill([
            'invoice_number' => $this->makeManualInvoiceNumber($cp, $periodStart),
            'customer_package_id' => $cp->id,
            'customer_id' => $cp->customer->id,
            'invoice_date' => $dueDate->format('Y-m-d'),
            'due_date' => $dueDate->toDateString(),
            'period_start' => $periodStart->toDateString(),
            'period_end' => $periodEnd->toDateString(),
            'status' => Invoices::STATUS_UNPAID,
            'invoice_type' => Invoices::TYPE_MANUAL,
            'amount' => $cp->package->price,
        ]);
        $invoice->save();

        // Add main charge line from package price
        $pkg = $cp->package;
        $price = $pkg ? (float) $pkg->price : 0.0;
        $desc = $pkg ? ("Langganan: {$pkg->name}") : 'Langganan';
        $this->addItem($invoice, 'charge', $desc, 1, $price);

        // Recalculate totals and persist
        $invoice->recalculateTotals();
        $invoice->save();

        return $invoice;
    }

    private function makeInvoiceNumber(CustomerPackages $cp, Carbon $periodStart): string
    {
        $ym = $periodStart->format('Ym');
        $latestInvoice = Invoices::query()
            ->whereYear('invoice_date', $periodStart->year)
            ->whereMonth('invoice_date', $periodStart->month)
            ->orderByDesc('invoice_number')
            ->first('invoice_number');

        if ($latestInvoice && preg_match('/INV-\d{6}-(\d{4})/', $latestInvoice->invoice_number, $matches)) {
            $nextSeq = (int) $matches[1] + 1;
        } else {
            $nextSeq = 1;
        }

        return sprintf('INV-%s-%04d', $ym, $nextSeq);
    }

    private function makeManualInvoiceNumber(CustomerPackages $cp, Carbon $periodStart): string
    {
        $ym = $periodStart->format('Ym');
        $latestInvoice = Invoices::query()
            ->where('invoice_type', Invoices::TYPE_MANUAL)
            ->whereYear('invoice_date', $periodStart->year)
            ->whereMonth('invoice_date', $periodStart->month)
            ->orderByDesc('invoice_number')
            ->first('invoice_number');

        if ($latestInvoice && preg_match('/INV-\d{6}-(\d{4})/', $latestInvoice->invoice_number, $matches)) {
            $nextSeq = (int) $matches[1] + 1;
        } else {
            $nextSeq = 1;
        }

        return sprintf('INV-MAN-%s-%04d', $ym, $nextSeq);
    }

    private function generateRemainingAmount(Invoices $invoice, $minAmount = 10000): float
    {
        $customer = $invoice->customerPackage->customer;

        // Find the last successful payment for this customer on any invoice.
        $lastPayment = Payment::query()
            ->whereHas('allocations.invoice.customerPackage', function (Builder $query) use ($customer) {
                $query->where('customer_id', $customer->id);
            })
            ->where('is_cancel', false) // Assuming 'paid' is the status for a successful payment.
            ->latest('payment_datetime')
            ->first();

        if (empty($lastPayment)) {
            // No previous payment, so the full amount is due.
            return (float) $invoice->amount;
        }

        $lastPaymentDate = Carbon::parse($lastPayment->payment_datetime);
        $currentInvoiceDate = Carbon::parse($invoice->due_date);

        // Assuming a fixed 30-day billing cycle for proration calculation.
        $daysInBillingCycle = 30;

        $daysSinceLastPayment = ceil($lastPaymentDate->diffInDays($currentInvoiceDate));

        if ($daysSinceLastPayment < $daysInBillingCycle) {
            // There is an overlap from the previous payment period.
            $packagePrice = (float) $invoice->customerPackage->package->price;
            $dailyRate = $packagePrice / $daysInBillingCycle;

            // Calculate the credit for the unused days from the previous cycle.
            $overlappingDays = $daysInBillingCycle - $daysSinceLastPayment;
            $creditAmount = $dailyRate * $overlappingDays;

            // The new amount is the full price minus the credit.
            $proratedAmount = $packagePrice - $creditAmount;

            // Ensure the amount is not negative and return thousand
            $amount = ceil(max(0, $proratedAmount) / 1000) * 1000;
            $this->hasAdjustment = true;

            return $amount < $minAmount ? $minAmount : $amount;
        }

        // If the last payment was more than a billing cycle ago, charge the full amount.
        return (float) $invoice->amount;
    }
}
