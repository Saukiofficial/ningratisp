<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PaymentMethod;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentMethodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PaymentMethod');
    }

    public function view(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('View:PaymentMethod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PaymentMethod');
    }

    public function update(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('Update:PaymentMethod');
    }

    public function delete(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('Delete:PaymentMethod');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PaymentMethod');
    }

    public function restore(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('Restore:PaymentMethod');
    }

    public function forceDelete(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('ForceDelete:PaymentMethod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PaymentMethod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PaymentMethod');
    }

    public function replicate(AuthUser $authUser, PaymentMethod $paymentMethod): bool
    {
        return $authUser->can('Replicate:PaymentMethod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PaymentMethod');
    }

}