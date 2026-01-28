<?php

namespace App\Jobs;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\CustomerConnection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class PingCustomerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Customer $customer)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $mikrotik = new MikrotikAPINative;
        $response = $mikrotik->ping($this->customer->remote_address, 4);

        if (isset($response['error'])) {
            Cache::increment('ping_all_customers_offline');
            Cache::increment('ping_all_customers_processed');

            return;
        }

        $packetLoss = 0;
        foreach ($response as $line) {
            if ($line['packet-loss'] > 0 && in_array($line['status'], ['packet rejected', 'timeout'])) {
                $packetLoss = $line['packet-loss'];
            }
        }

        $status = $packetLoss < 50 ? 'online' : 'offline';

        if ($status === 'online') {
            Cache::increment('ping_all_customers_online');
        } else {
            Cache::increment('ping_all_customers_offline');
        }

        CustomerConnection::query()->updateOrCreate(
            ['customer_id' => $this->customer->id],
            [
                'ip_address' => $this->customer->remote_address,
                'status' => $status,
                'ping_count' => $response[count($response) - 1]['received'] ?? 0,
                'packet_loss' => $packetLoss,
                'avg_rtt' => $response[count($response) - 1]['avg-rtt'] ?? null,
                'last_seen' => now(),
                'last_ping_output' => json_encode($response),
            ]
        );
        Cache::increment('ping_all_customers_processed');
    }
}
