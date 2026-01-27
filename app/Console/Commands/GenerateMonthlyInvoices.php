<?php

namespace App\Console\Commands;

use App\Services\InvoiceService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class GenerateMonthlyInvoices extends Command
{
    protected $signature = 'invoices:generate {--date=}';

    protected $description = 'Generate monthly invoices for active customer packages';

    public function handle(InvoiceService $service): int
    {
        $dateOption = $this->option('date');
        $date = $dateOption ? Carbon::parse($dateOption) : Carbon::now();

        $count = $service->generateMonthlyInvoicesForDate($date);

        $this->info("Generated {$count} invoice(s) for " . $date->format('F Y'));
        return self::SUCCESS;
    }
}
