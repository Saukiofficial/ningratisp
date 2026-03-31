<?php

namespace App\Jobs;

use App\Helpers\MikrotikAPINative;
use App\Models\Customer;
use App\Models\Enum\RolesEnum;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ActivateCustomerInternetJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Customer $customer,
        public array $data = [],
        public ?User $user = null,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $response = app(MikrotikAPINative::class)->isolirClient(
            $this->customer->username,
            false
        );

        if (isset($response['error'])) {
            $admin = User::role(RolesEnum::SUPER_ADMIN)->first();
            if ($admin) {
                Notification::make()
                    ->title('Active internet failed : ' . $this->customer->username)
                    ->body(json_encode($response))
                    ->danger()
                    ->sendToDatabase($this->user);
            }
        }
    }
}
