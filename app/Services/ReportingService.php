<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class ReportingService
{
    public function summarizeRevenueByMonth(Carbon $from, Carbon $to): array
    {
        return [];
    }

    public function agingReceivables(): array
    {
        return [];
    }

    public function customerArSnapshot(int $customerId): array
    {
        return [];
    }

    public function exportInvoicesCsv(array $filters = []): string
    {
        // Placeholder: return path to generated CSV
        return '';
    }

    public function exportPaymentsCsv(array $filters = []): string
    {
        // Placeholder: return path to generated CSV
        return '';
    }
}
