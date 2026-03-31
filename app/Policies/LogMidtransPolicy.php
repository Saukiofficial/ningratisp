<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LogMidtrans;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogMidtransPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LogMidtrans');
    }

    public function view(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('View:LogMidtrans');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LogMidtrans');
    }

    public function update(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('Update:LogMidtrans');
    }

    public function delete(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('Delete:LogMidtrans');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LogMidtrans');
    }

    public function restore(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('Restore:LogMidtrans');
    }

    public function forceDelete(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('ForceDelete:LogMidtrans');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LogMidtrans');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LogMidtrans');
    }

    public function replicate(AuthUser $authUser, LogMidtrans $logMidtrans): bool
    {
        return $authUser->can('Replicate:LogMidtrans');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LogMidtrans');
    }

}