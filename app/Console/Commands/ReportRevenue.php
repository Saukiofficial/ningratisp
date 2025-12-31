<?php

namespace App\Console\Commands;

use App\Models\Invoices;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportRevenue extends Command
{
    protected $signature = 'report:revenue {--start=} {--end=} {--group=none}';

    protected $description = 'Revenue report by payments (incoming - refunds) and billed (invoices), with optional grouping by day or month.';

    public function handle(): int
    {
        $startOpt = $this->option('start');
        $endOpt = $this->option('end');
        $group = strtolower($this->option('group') ?: 'none'); // none|day|month

        $start = $startOpt ? Carbon::parse($startOpt)->startOfDay() : null;
        $end = $endOpt ? Carbon::parse($endOpt)->endOfDay() : null;

        $this->info('Revenue report' . ($start || $end ? (': ' . ($start?->toDateString() ?? '...') . ' to ' . ($end?->toDateString() ?? '...')) : ''));

        // Payments-based revenue
        $payments = Payment::query()->where('is_cancel', false);
        if ($start) $payments->where('payment_datetime', '>=', $start);
        if ($end) $payments->where('payment_datetime', '<=', $end);

        if ($group === 'day' || $group === 'month') {
            $format = $group === 'day' ? '%Y-%m-%d' : '%Y-%m';
            $rows = $payments
                ->selectRaw("DATE_FORMAT(payment_datetime, '{$format}') as period")
                ->selectRaw("SUM(CASE WHEN payment_type = 'incoming' THEN total_amount ELSE 0 END) as incoming")
                ->selectRaw("SUM(CASE WHEN payment_type = 'refund' THEN total_amount ELSE 0 END) as refunds")
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            $this->line(str_pad('Period', 12) . str_pad('Incoming', 14) . str_pad('Refunds', 14) . 'Net');
            $totalIncoming = $totalRefunds = 0.0;
            foreach ($rows as $r) {
                $net = (float) $r->incoming - (float) $r->refunds;
                $totalIncoming += (float) $r->incoming;
                $totalRefunds += (float) $r->refunds;
                $this->line(str_pad($r->period, 12) . str_pad(number_format($r->incoming, 2), 14) . str_pad(number_format($r->refunds, 2), 14) . number_format($net, 2));
            }
            $this->line(str_repeat('-', 54));
            $this->line(str_pad('TOTAL', 12) . str_pad(number_format($totalIncoming, 2), 14) . str_pad(number_format($totalRefunds, 2), 14) . number_format($totalIncoming - $totalRefunds, 2));
        } else {
            $incoming = (float) (clone $payments)->where('payment_type', 'incoming')->sum('total_amount');
            $refunds = (float) (clone $payments)->where('payment_type', 'refund')->sum('total_amount');
            $net = $incoming - $refunds;
            $this->line('Payments - Incoming: ' . number_format($incoming, 2));
            $this->line('Payments - Refunds : ' . number_format($refunds, 2));
            $this->line('Payments - Net     : ' . number_format($net, 2));
        }

        // Invoices (billed) for context
        $invoices = Invoices::query();
        if ($start) $invoices->where('invoice_date', '>=', $start->toDateString());
        if ($end) $invoices->where('invoice_date', '<=', $end->toDateString());

        if ($group === 'day' || $group === 'month') {
            $format = $group === 'day' ? '%Y-%m-%d' : '%Y-%m';
            $rows = $invoices
                ->selectRaw("DATE_FORMAT(invoice_date, '{$format}') as period")
                ->selectRaw('SUM(total_amount) as billed')
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            $this->line('');
            $this->line(str_pad('Period', 12) . 'Billed');
            $totalBilled = 0.0;
            foreach ($rows as $r) {
                $totalBilled += (float) $r->billed;
                $this->line(str_pad($r->period, 12) . number_format($r->billed, 2));
            }
            $this->line(str_repeat('-', 28));
            $this->line(str_pad('TOTAL', 12) . number_format($totalBilled, 2));
        } else {
            $billed = (float) $invoices->sum('total_amount');
            $this->line('Billed (Invoices): ' . number_format($billed, 2));
        }

        return self::SUCCESS;
    }
}
