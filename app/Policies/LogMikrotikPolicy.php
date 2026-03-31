<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LogMikrotik;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogMikrotikPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LogMikrotik');
    }

    public function view(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('View:LogMikrotik');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LogMikrotik');
    }

    public function update(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('Update:LogMikrotik');
    }

    public function delete(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('Delete:LogMikrotik');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LogMikrotik');
    }

    public function restore(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('Restore:LogMikrotik');
    }

    public function forceDelete(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('ForceDelete:LogMikrotik');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LogMikrotik');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LogMikrotik');
    }

    public function replicate(AuthUser $authUser, LogMikrotik $logMikrotik): bool
    {
        return $authUser->can('Replicate:LogMikrotik');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LogMikrotik');
    }

}