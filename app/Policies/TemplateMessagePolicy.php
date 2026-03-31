<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TemplateMessage;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplateMessagePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TemplateMessage');
    }

    public function view(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('View:TemplateMessage');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TemplateMessage');
    }

    public function update(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('Update:TemplateMessage');
    }

    public function delete(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('Delete:TemplateMessage');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TemplateMessage');
    }

    public function restore(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('Restore:TemplateMessage');
    }

    public function forceDelete(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('ForceDelete:TemplateMessage');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TemplateMessage');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TemplateMessage');
    }

    public function replicate(AuthUser $authUser, TemplateMessage $templateMessage): bool
    {
        return $authUser->can('Replicate:TemplateMessage');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TemplateMessage');
    }

}