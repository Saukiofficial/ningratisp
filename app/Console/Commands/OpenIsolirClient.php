<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Console\Command;

class OpenIsolirClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:open-isolir-client';

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
        $date = now();
        $month = strtolower($date->format('M'));

        // get user with comments expired or date moenth
        $mikrotikUsers = (new MikrotikAPINative())->getPppSecrets(false);
        $mikrotikUsers = array_filter(
            $mikrotikUsers,
            fn($user) => !empty($user['comment'] ?? null) &&
                (
                    str_contains($user['comment'], $month)
                    || str_contains(strtolower($user['comment']), 'expired')
                )
        );

        $usernames = $usernamesKey = [];
        foreach ($mikrotikUsers as $user) {
            $usernames[] =  $user['name'];
            $usernamesKey[$user['name']] = $user;
        }

        $paidCustomers = Customer::with('invoices.payments')
            ->whereHas('invoices', function ($query) {
                $query->where('payment_status', Payment::STATUS_PAID);
            })
            ->whereHas(
                'invoices.payments',
                fn($query) => $query->whereMonth('payment_datetime', $date->format('m'))
                    ->whereYear('payment_datetime', $date->format('Y'))
            )
            ->whereIn('username', $usernames)
            ->get(['username']);

        $api = (new MikrotikAPINative);
        $nextMonth = strtolower($date->addMonth()->format('M/11/Y 06:00:00'));

        // cek customer sudah bayar, baik status expired atau belum
        $processUsers = [];
        foreach ($paidCustomers as $customer) {
            if (isset($usernamesKey[$customer->username])) {

                $response = $api->getPppUser($customer->username);
                if (
                    str_contains($response['comment'], $month)
                    || str_contains(strtolower($response['comment']), 'expired')
                ) {
                    $processUsers[] = $usernamesKey[$customer->username];
                }
            }
        }

        // flag lunas
        foreach ($processUsers as $user) {
            $this->info('Username : ' . $user['name']);
            $response = $api->request('/ppp/secret/set', [
                '.id' => $user['.id'],
                'comment' => 'lunas'
            ]);
            $this->info(json_encode($response));
            $this->newLine();
        }
    }
}
