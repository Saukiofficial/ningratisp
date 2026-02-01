<?php

namespace App\Console\Commands;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
            fn($user) => !empty($user['comment'] ?? null)
        );

        $filteredUsers = $filteredUsername = $expiredUsers = $expiredUsername = [];
        $previousExpired = Customer::query()->whereNotNull('isolir_at')->get()?->keyBy('username')?->toArray();
        foreach ($mikrotikUsers as $user) {
            $filteredUsers[$user['name']] = $user;
            $filteredUsername[] = $user['name'];

            // new expired users
            if (str_contains(strtolower($user['comment']), 'expired') && !isset($previousExpired[$user['name']])) {
                $expiredUsers[$user['name']] = $user;
                $expiredUsername[] = $user['name'];
            }
        }

        if (empty($expiredUsername)) {
            $this->error('Expired username empty');
            return;
        }

        $customers = Customer::query()->whereIn('username', $expiredUsername)
            ->get([

                'id',
                'username',
                'ppp_profile_id',
                'isolir_at',
                'comment'
            ])->keyBy('username');

        $expiredUserCount = 0;
        $expiredUsername = !empty($previousExpired) ? array_merge(array_keys($previousExpired), $expiredUsername) : $expiredUsername;
        foreach ($expiredUsers as $username => $expiredUser) {
            if (!empty($customers->has($username)) && empty($customers->get($username)->isolir_at)) {
                $customer = $customers->get($username);

                $customer->update([
                    'isolir_at' => (!empty($expiredUser['last-logged-out']) && !str_contains($expiredUser['last-logged-out'], '1970-01-01')) ?
                        Date::parse($expiredUser['last-logged-out']) : now(),
                    'comment' => $expiredUser['comment']
                ]);
                $customer->save();
                $expiredUserCount++;
            }
        }

        Customer::query()->whereNotIn('username', $expiredUsername)
            ->update([
                'isolir_at' => null,
                'comment' => null
            ]);

        $this->info('Success updated expired : ' . $expiredUserCount);
    }
}
