<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ParentNote;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParentNotePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ParentNote');
    }

    public function view(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('View:ParentNote');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ParentNote');
    }

    public function update(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('Update:ParentNote');
    }

    public function delete(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('Delete:ParentNote');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ParentNote');
    }

    public function restore(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('Restore:ParentNote');
    }

    public function forceDelete(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('ForceDelete:ParentNote');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ParentNote');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ParentNote');
    }

    public function replicate(AuthUser $authUser, ParentNote $parentNote): bool
    {
        return $authUser->can('Replicate:ParentNote');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ParentNote');
    }

}