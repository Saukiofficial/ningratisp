<?php

namespace App\Policies\Customer;

use App\Models\Customer;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Auth\Access\Response;

class VirtualAccountPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Customer $customer, VirtualAccount $virtualAccount): bool
    {
        return $customer->id === $virtualAccount->invoice->customerPackage->customer_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VirtualAccount $virtualAccount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VirtualAccount $virtualAccount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, VirtualAccount $virtualAccount): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, VirtualAccount $virtualAccount): bool
    {
        return false;
    }
}
