<?php

namespace App\Services;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Invoices;
use App\Models\Payment;
use Illuminate\Support\Facades\Date;

class CustomerIsolirService
{
    public function __construct(protected MikrotikAPINative $api)
    {
    }

    /**
     * Get tasks for syncing isolir status from MikroTik to DB.
     */
    public function getSyncTasks(): array
    {
        $mikrotikUsers = $this->api->getPppSecrets(fromCache: false);
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

        $customers = Customer::query()->whereIn('username', array_column($mikrotikUsers, 'name'))
            ->get([
                'id',
                'username',
                'ppp_profile_id',
                'isolir_at',
                'comment',
            ]);

        $tasks = [];
        foreach ($customers as $customer) {
            $username = $customer->username;
            $isExpiredInMikrotik = in_array($username, $currentlyExpiredUsernames);

            if ($isExpiredInMikrotik) {
                if (empty($customer->isolir_at)) {
                    $expiredUser = $expiredUsersData[$username];
                    $tasks[] = [
                        'customer_id' => $customer->id,
                        'username' => $username,
                        'action' => 'set_isolir',
                        'comment' => $expiredUser['comment'],
                        'last_logged_out' => $expiredUser['last-logged-out'] ?? null,
                    ];
                }
            } else {
                if (! empty($customer->isolir_at)) {
                    $tasks[] = [
                        'customer_id' => $customer->id,
                        'username' => $username,
                        'action' => 'clear_isolir',
                    ];
                }
            }
        }

        return $tasks;
    }

    /**
     * Process a single sync task.
     */
    public function processSyncTask(array $task): bool
    {
        $customer = Customer::find($task['customer_id']);
        if (! $customer) {
            return false;
        }

        if ($task['action'] === 'set_isolir') {
            $isolirAt = (! empty($task['last_logged_out']) && ! str_contains($task['last_logged_out'], '1970-01-01'))
                ? Date::parse($task['last_logged_out'])
                : now();

            return $customer->update([
                'isolir_at' => $isolirAt,
                'comment' => $task['comment'],
            ]);
        }

        if ($task['action'] === 'clear_isolir') {
            return $customer->update([
                'isolir_at' => null,
                'comment' => null,
            ]);
        }

        return false;
    }

    /**
     * Get tasks for opening isolir for paid customers in MikroTik.
     */
    public function getOpenTasks(): array
    {
        $date = now();
        $month = strtolower($date->format('M'));

        $mikrotikUsers = $this->api->getPppSecrets(false);
        $mikrotikUsers = array_filter(
            $mikrotikUsers,
            fn($user) => !empty($user['comment'] ?? null) &&
                (
                    str_contains($user['comment'], $month)
                    || str_contains(strtolower($user['comment']), 'expired')
                )
        );

        $usernames = [];
        $usernamesKey = [];
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

        $tasks = [];
        foreach ($paidCustomers as $customer) {
            if (isset($usernamesKey[$customer->username])) {
                // Double check comment from fresh API call if needed, but here we use the bulk list
                // To be safe like the command, we could re-fetch but that's slow for bulk.
                // The command does re-fetch in the loop. We will follow that for accuracy if preferred,
                // but let's stick to the filtered list for efficiency first.
                $tasks[] = [
                    'username' => $customer->username,
                    'mikrotik_id' => $usernamesKey[$customer->username]['.id'],
                    'action' => 'set_lunas',
                ];
            }
        }

        return $tasks;
    }

    /**
     * Process a single open task.
     */
    public function processOpenTask(array $task): array
    {
        // Re-check user comment to be safe like the command
        $user = $this->api->getPppUser($task['username']);
        $date = now();
        $month = strtolower($date->format('M'));

        if (
            isset($user['comment']) &&
            (
                str_contains($user['comment'], $month)
                || str_contains(strtolower($user['comment']), 'expired')
            )
        ) {
            $response = $this->api->request('/ppp/secret/set', [
                '.id' => $user['.id'],
                'comment' => 'lunas'
            ]);

            return [
                'success' => !isset($response['error']),
                'response' => $response,
            ];
        }

        return [
            'success' => true,
            'message' => 'User already lunas or not matching criteria',
        ];
    }
}
