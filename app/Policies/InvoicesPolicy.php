<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Invoices;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicesPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Invoices');
    }

    public function view(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('View:Invoices');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Invoices');
    }

    public function update(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('Update:Invoices');
    }

    public function delete(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('Delete:Invoices');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Invoices');
    }

    public function restore(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('Restore:Invoices');
    }

    public function forceDelete(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('ForceDelete:Invoices');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Invoices');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Invoices');
    }

    public function replicate(AuthUser $authUser, Invoices $invoices): bool
    {
        return $authUser->can('Replicate:Invoices');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Invoices');
    }

}