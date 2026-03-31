<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Fee;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Fee');
    }

    public function view(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('View:Fee');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Fee');
    }

    public function update(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('Update:Fee');
    }

    public function delete(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('Delete:Fee');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Fee');
    }

    public function restore(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('Restore:Fee');
    }

    public function forceDelete(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('ForceDelete:Fee');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Fee');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Fee');
    }

    public function replicate(AuthUser $authUser, Fee $fee): bool
    {
        return $authUser->can('Replicate:Fee');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Fee');
    }

}