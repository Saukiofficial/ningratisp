<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CustomerConnection;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerConnectionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CustomerConnection');
    }

    public function view(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('View:CustomerConnection');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CustomerConnection');
    }

    public function update(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('Update:CustomerConnection');
    }

    public function delete(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('Delete:CustomerConnection');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CustomerConnection');
    }

    public function restore(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('Restore:CustomerConnection');
    }

    public function forceDelete(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('ForceDelete:CustomerConnection');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CustomerConnection');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CustomerConnection');
    }

    public function replicate(AuthUser $authUser, CustomerConnection $customerConnection): bool
    {
        return $authUser->can('Replicate:CustomerConnection');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CustomerConnection');
    }

}