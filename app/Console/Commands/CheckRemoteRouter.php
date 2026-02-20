<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Storage;

class CheckRemoteRouter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-remote-router 
                            {--timeout=5 : The timeout in seconds per request}
                            {--concurrency=10 : How many requests to send at once}
                            {--export= : Optional file path to save the JSON output (e.g., router_status.json)}
                            {--force : re-assign flag remote}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check connectivity of customer remote routers concurrently';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->components->info('Fetching customer data...');

        // 1. Fetch Customers
        $customers = Customer::query()
            ->when(
                !$this->option('force'),
                fn(Builder $q) => $q->where('can_remote', false)
            )
            ->whereNotNull('remote_address')
            ->get(['id', 'username', 'remote_address', 'can_remote']);

        $total = $customers->count();

        if ($total === 0) {
            $this->components->warn('No customers with remote addresses found.');
            return;
        }

        $timeout = (int) $this->option('timeout');
        $concurrency = (int) $this->option('concurrency');

        $this->line("Found <comment>{$total}</comment> routers.");
        $this->line("Running with concurrency: <comment>{$concurrency}</comment> | Timeout: <comment>{$timeout}s</comment>");
        $this->newLine();

        // 2. Initialize Progress Bar
        $bar = $this->output->createProgressBar($total);
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% -- Batch processing...');
        $bar->start();

        $results = [];
        $onlineCount = 0;
        $offlineCount = 0;

        // 3. Process in Chunks (Pool)
        // We chunk the customers to prevent memory overflows if you have thousands
        foreach ($customers->chunk($concurrency) as $chunk) {

            // Execute concurrent requests
            $responses = Http::pool(function (Pool $pool) use ($chunk, $timeout) {
                foreach ($chunk as $customer) {
                    // We use as($id) so we can map the response back to the customer
                    $pool->as($customer->id)
                        ->timeout($timeout)
                        ->connectTimeout($timeout)
                        ->get($customer->remote_address);
                }
            });

            // Process the batch results
            foreach ($chunk as $customer) {
                $response = $responses[$customer->id] ?? null;

                $isSuccess = false;
                $details = 'No Response';

                // Check if we got a valid response object or an exception
                if ($response instanceof \Illuminate\Http\Client\Response) {
                    $isSuccess = $response->successful();
                    $details = $isSuccess ? $response->status() . ' OK' : 'HTTP ' . $response->status();
                } elseif ($response instanceof \Exception) {
                    $details = $response->getMessage();
                }

                // Update Stats
                if ($isSuccess) {
                    $onlineCount++;
                    $customer->update(['can_remote' => true]);
                } else {
                    $offlineCount++;
                }

                $results[] = [
                    'username' => $customer->username,
                    'remote_address' => $customer->remote_address,
                    'status' => $isSuccess ? 'ONLINE' : 'OFFLINE',
                    'details' => $details,
                    'timestamp' => now()->toDateTimeString(),
                ];
            }

            // Advance progress bar by the chunk size
            $bar->advance($chunk->count());
        }

        $bar->finish();
        $this->newLine(2);

        // 4. Output Summary Table (Limit to last 10 or errors if list is huge, usually show all)
        // For CLI readability, we colorize the status row for the table
        $tableRows = array_map(function ($row) {
            $status = $row['status'] === 'ONLINE'
                ? '<info>ONLINE</info>'
                : '<error>OFFLINE</error>';
            return [
                $row['username'],
                $row['remote_address'],
                $status,
                $row['details']
            ];
        }, $results);

        $this->components->info('Check Complete.');

        // Show table (limit if too many, optional)
        $this->table(['Username', 'Address', 'Status', 'Details'], $tableRows);

        $this->info("Summary: <info>{$onlineCount} Online</info> | <error>{$offlineCount} Offline</error>");

        // 5. Handle JSON Export
        if ($path = $this->option('export')) {
            $json = json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

            // If path is absolute or relative to current execution
            if (file_put_contents($path, $json) !== false) {
                $this->newLine();
                $this->components->success("Exported results to: {$path}");
            } else {
                $this->components->error("Failed to write to file: {$path}");
            }
        }
    }
}
