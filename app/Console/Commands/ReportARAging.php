<?php

namespace App\Console\Commands;

use App\Models\AccountReceivable;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class ReportARAging extends Command
{
    protected $signature = 'report:ar-aging {--as-of=} {--customer=} {--export=}';

    protected $description = 'Accounts Receivable aging report with buckets: Current, 1-30, 31-60, 61-90, 90+ days';

    public function handle(): int
    {
        $asOfOpt = $this->option('as-of');
        $asOf = $asOfOpt ? Carbon::parse($asOfOpt) : Carbon::now();
        $customerId = $this->option('customer');
        $export = $this->option('export'); // csv | null

        $query = AccountReceivable::query()
            ->with('customer')
            ->where('status', '!=', 'written_off')
            ->where('balance', '>', 0);

        if ($customerId) {
            $query->where('customer_id', (int) $customerId);
        }

        $rows = $query->get();

        $buckets = [
            'current' => 0.0,
            '1-30' => 0.0,
            '31-60' => 0.0,
            '61-90' => 0.0,
            '90+' => 0.0,
        ];

        foreach ($rows as $row) {
            $balance = (float) $row->balance;
            $due = $row->due_date ? Carbon::parse($row->due_date) : null;
            if (!$due) {
                $buckets['current'] += $balance;
                continue;
            }
            if ($due->toDateString() >= $asOf->toDateString()) {
                $buckets['current'] += $balance;
                continue;
            }
            $days = $due->diffInDays($asOf);
            if ($days <= 30) {
                $buckets['1-30'] += $balance;
            } elseif ($days <= 60) {
                $buckets['31-60'] += $balance;
            } elseif ($days <= 90) {
                $buckets['61-90'] += $balance;
            } else {
                $buckets['90+'] += $balance;
            }
        }

        $total = array_sum($buckets);

        if ($export === 'csv') {
            $headers = ['Bucket', 'Amount'];
            $this->line(implode(',', $headers));
            foreach ($buckets as $bucket => $amt) {
                $this->line($bucket . ',' . number_format($amt, 2, '.', ''));
            }
            $this->line('Total,' . number_format($total, 2, '.', ''));
        } else {
            $this->info('AR Aging as of ' . $asOf->toDateString());
            foreach ($buckets as $bucket => $amt) {
                $this->line(str_pad($bucket, 8) . ' : ' . number_format($amt, 2));
            }
            $this->line(str_repeat('-', 24));
            $this->line('Total   : ' . number_format($total, 2));
        }

        return self::SUCCESS;
    }
}
