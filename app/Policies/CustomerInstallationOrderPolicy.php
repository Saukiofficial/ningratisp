<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CustomerInstallationOrder;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerInstallationOrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CustomerInstallationOrder');
    }

    public function view(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        return $authUser->can('View:CustomerInstallationOrder');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CustomerInstallationOrder');
    }

    public function update(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        if ($customerInstallationOrder->status == CustomerInstallationOrder::STATUS_DONE) {
            return false;
        }

        return $authUser->can('Update:CustomerInstallationOrder');
    }

    public function delete(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        if ($customerInstallationOrder->status == CustomerInstallationOrder::STATUS_DONE) {
            return false;
        }

        return $authUser->can('Delete:CustomerInstallationOrder');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CustomerInstallationOrder');
    }

    public function restore(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        return $authUser->can('Restore:CustomerInstallationOrder');
    }

    public function forceDelete(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        if ($customerInstallationOrder->status == CustomerInstallationOrder::STATUS_DONE) {
            return false;
        }

        return $authUser->can('ForceDelete:CustomerInstallationOrder');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CustomerInstallationOrder');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CustomerInstallationOrder');
    }

    public function replicate(AuthUser $authUser, CustomerInstallationOrder $customerInstallationOrder): bool
    {
        return $authUser->can('Replicate:CustomerInstallationOrder');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CustomerInstallationOrder');
    }
}
