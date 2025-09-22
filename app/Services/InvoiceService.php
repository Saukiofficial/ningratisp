<?php

namespace App\Services;

use App\Models\CustomerPackages;
use App\Models\Invoices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
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
                ->where('customer_package_id', $cp->id)
                ->whereDate('period_start', $periodStart->toDateString())
                ->where('status', '!=', Invoices::STATUS_CANCELLED)
                ->exists();

            if ($exists) {
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

    public function generateInvoiceForCustomerPackage(CustomerPackages $cp, Carbon $periodStart, Carbon $periodEnd, Carbon $date = null): Invoices
    {
        // Create invoice shell
        $invoice_date = $date?->toDateString() ?? Carbon::now()->toDateString();
        $due_date = $date?->copy()->addDays(30)->toDateString() ?? Carbon::now()->copy()->addDays(30)->toDateString();
        $invoice = new Invoices();
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
        $invoice->save();

        // Add main charge line from package price
        $pkg = $cp->package;
        $price = $pkg ? (float) $pkg->price : 0.0;
        $desc = $pkg ? ("Langganan Bulanan: {$pkg->name}") : 'Langganan Bulanan';
        $this->addItem($invoice, 'charge', $desc, 1, $price);

        // Recalculate totals and persist
        $invoice->recalculateTotals();
        $invoice->save();

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
            if (!$customerPackage) {
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
        $invoice = new Invoices();
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
        $seq = Invoices::query()
            ->whereYear('invoice_date', (int) $periodStart->format('Y'))
            ->whereMonth('invoice_date', (int) $periodStart->format('m'))
            ->count() + 1;
        return sprintf('INV-%s-%04d', $ym, $seq);
    }

    private function makeManualInvoiceNumber(CustomerPackages $cp, Carbon $periodStart): string
    {
        $ym = $periodStart->format('Ym');
        $seq = Invoices::query()
            ->where('invoice_type', Invoices::TYPE_MANUAL)
            ->whereYear('invoice_date', (int) $periodStart->format('Y'))
            ->whereMonth('invoice_date', (int) $periodStart->format('m'))
            ->count() + 1;
        return sprintf('INV-MAN-%s-%04d', $ym, $seq);
    }
}
