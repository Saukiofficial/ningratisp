<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;

class IsolirUnpaidCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:isolir-unpaid-customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $secrets = (new MikrotikAPINative)->getPppSecrets(false);
        $secrets = array_reduce(
            $secrets,
            function ($carry, $user) {
                if (empty($user['comment'] ?? null)) {
                    $carry[$user['name']] = $user;
                }

                return $carry;
            },
            []
        );

        $year = now()->format('Y');
        $month = now()->format('m');
        $customers = Customer::query()
            ->where('auto_isolir', true)
            ->whereHas(
                'invoices',
                fn (Builder $q) => $q->whereYear('invoice_date', $year)->whereMonth('invoice_date', $month)
            )
            ->whereDoesntHave(
                'invoices.payments',
                fn (Builder $q) => $q->whereYear('payment_datetime', $year)->whereMonth('payment_datetime', $month)
            )
            ->whereNull('isolir_at')

            ->get();

        $assignedUsers = [];
        foreach ($customers as $customer) {
            if (isset($secrets[$customer->username])) {
                $assignedUsers[$customer->username] = $secrets[$customer->username]['.id'];
            }
        }

        $api = (new MikrotikAPINative);
        foreach ($assignedUsers as $username => $id) {
            $data = [
                '.id' => $id,
                'comment' => 'isolir',
                // 'comment' => strtolower(now()->addMonth()->format('M/11/Y 06:00:00 | ')),
            ];
            $response = $api->request('/ppp/secret/set', $data);
            $this->line($username);
            $this->line(json_encode($data, JSON_PRETTY_PRINT));
            $this->line(json_encode($response, JSON_PRETTY_PRINT));
            $this->newLine();
        }
    }
}
