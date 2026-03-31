<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Voucher;
use Illuminate\Auth\Access\HandlesAuthorization;

class VoucherPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Voucher');
    }

    public function view(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('View:Voucher');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Voucher');
    }

    public function update(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('Update:Voucher');
    }

    public function delete(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('Delete:Voucher');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Voucher');
    }

    public function restore(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('Restore:Voucher');
    }

    public function forceDelete(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('ForceDelete:Voucher');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Voucher');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Voucher');
    }

    public function replicate(AuthUser $authUser, Voucher $voucher): bool
    {
        return $authUser->can('Replicate:Voucher');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Voucher');
    }

}