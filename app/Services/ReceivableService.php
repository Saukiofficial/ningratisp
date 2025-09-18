<?php

namespace App\Services;

use App\Models\AccountReceivable;
use App\Models\Customer;
use App\Models\Invoices;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReceivableService
{
    public function syncForInvoice(Invoices $invoice): ?AccountReceivable
    {
        $customerId = $invoice->customerPackage?->customer_id;
        if (!$customerId) {
            return null;
        }

        return DB::transaction(function () use ($invoice, $customerId) {
            /** @var AccountReceivable $ar */
            $ar = AccountReceivable::query()->firstOrNew([
                'invoice_id' => $invoice->id,
            ]);

            $ar->customer_id = $customerId;
            $ar->amount = (float) ($invoice->total_amount ?? 0);
            $ar->amount_paid = (float) ($invoice->paid_amount ?? 0);
            $ar->due_date = $invoice->due_date;
            // status & balance recalculated on model saving
            $ar->save();

            return $ar;
        });
    }

    public function syncAll(?Carbon $since = null): int
    {
        $query = Invoices::query()->with('customerPackage');
        if ($since) {
            $query->where('updated_at', '>=', $since->toDateTimeString());
        }

        $count = 0;
        $query->orderBy('updated_at')->chunk(200, function ($invoices) use (&$count) {
            foreach ($invoices as $invoice) {
                $ar = $this->syncForInvoice($invoice);
                if ($ar) {
                    $count++;
                }
            }
        });

        return $count;
    }

    public function syncForCustomer(Customer $customer): int
    {
        $count = 0;
        $customer->load(['invoices' => function ($q) {
            $q->with('customerPackage');
        }]);
        foreach ($customer->invoices as $invoice) {
            $ar = $this->syncForInvoice($invoice);
            if ($ar) {
                $count++;
            }
        }
        return $count;
    }
}
