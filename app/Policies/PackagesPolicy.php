<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Packages;
use Illuminate\Auth\Access\HandlesAuthorization;

class PackagesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Packages');
    }

    public function view(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('View:Packages');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Packages');
    }

    public function update(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('Update:Packages');
    }

    public function delete(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('Delete:Packages');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Packages');
    }

    public function restore(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('Restore:Packages');
    }

    public function forceDelete(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('ForceDelete:Packages');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Packages');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Packages');
    }

    public function replicate(AuthUser $authUser, Packages $packages): bool
    {
        return $authUser->can('Replicate:Packages');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Packages');
    }

}