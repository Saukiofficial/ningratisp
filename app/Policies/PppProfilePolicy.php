<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PppProfile;
use Illuminate\Auth\Access\HandlesAuthorization;

class PppProfilePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PppProfile');
    }

    public function view(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('View:PppProfile');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PppProfile');
    }

    public function update(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('Update:PppProfile');
    }

    public function delete(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('Delete:PppProfile');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PppProfile');
    }

    public function restore(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('Restore:PppProfile');
    }

    public function forceDelete(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('ForceDelete:PppProfile');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PppProfile');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PppProfile');
    }

    public function replicate(AuthUser $authUser, PppProfile $pppProfile): bool
    {
        return $authUser->can('Replicate:PppProfile');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PppProfile');
    }

}