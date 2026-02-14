<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;

use function Symfony\Component\Clock\now;

class SyncCustomerIsolir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-customer-isolir';

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
        $mikrotikUsers = (new MikrotikAPINative)->getPppSecrets();
        $mikrotikUsers = array_filter(
            $mikrotikUsers,
            fn ($user) => ! empty($user['comment'] ?? null)
        );

        $currentlyExpiredUsernames = [];
        $expiredUsersData = [];

        foreach ($mikrotikUsers as $user) {
            if (str_contains(strtolower($user['comment']), 'expired')) {
                $currentlyExpiredUsernames[] = $user['name'];
                $expiredUsersData[$user['name']] = $user;
            }
        }

        if (empty($currentlyExpiredUsernames)) {
            $this->error('Expired username empty');

            return;
        }

        $customers = Customer::query()->whereIn('username', array_column($mikrotikUsers, 'name'))
            ->get([

                'id',
                'username',
                'ppp_profile_id',
                'isolir_at',
                'comment',
            ])->keyBy('username');

        $expiredUserCount = 0;
        foreach ($customers as $username => $customer) {

            // CASE 1: User is expired in Mikrotik
            if (in_array($username, $currentlyExpiredUsernames)) {

                if (empty($customer->isolir_at)) {

                    $expiredUser = $expiredUsersData[$username];

                    $customer->update([
                        'isolir_at' => (! empty($expiredUser['last-logged-out']) && ! str_contains($expiredUser['last-logged-out'], '1970-01-01'))
                            ? Date::parse($expiredUser['last-logged-out'])
                            : now(),
                        'comment' => $expiredUser['comment'],
                    ]);

                    $expiredUserCount++;
                }
            } else {
                // CASE 2: Previously expired but now active → clear
                if (! empty($customer->isolir_at)) {
                    $customer->update([
                        'isolir_at' => null,
                        'comment' => null,
                    ]);
                }
            }
        }

        $this->info('Success updated expired : '.$expiredUserCount);
    }
}
