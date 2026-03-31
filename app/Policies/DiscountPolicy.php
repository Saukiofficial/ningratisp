<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Discount;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Discount');
    }

    public function view(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('View:Discount');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Discount');
    }

    public function update(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('Update:Discount');
    }

    public function delete(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('Delete:Discount');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Discount');
    }

    public function restore(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('Restore:Discount');
    }

    public function forceDelete(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('ForceDelete:Discount');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Discount');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Discount');
    }

    public function replicate(AuthUser $authUser, Discount $discount): bool
    {
        return $authUser->can('Replicate:Discount');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Discount');
    }

}