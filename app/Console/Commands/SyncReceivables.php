<?php

namespace App\Console\Commands;

use App\Services\ReceivableService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SyncReceivables extends Command
{
    protected $signature = 'receivables:sync {--since=}';

    protected $description = 'Sync Account Receivables from invoices (optionally since a date)';

    public function handle(ReceivableService $service): int
    {
        $sinceOpt = $this->option('since');
        $since = null;
        if ($sinceOpt) {
            try {
                $since = Carbon::parse($sinceOpt);
            } catch (\Throwable $e) {
                $this->error('Invalid --since date. Expected format like 2025-08-01.');
                return self::INVALID;
            }
        }

        $count = $service->syncAll($since);
        $this->info("Synced AR for {$count} invoice(s).");
        return self::SUCCESS;
    }
}
