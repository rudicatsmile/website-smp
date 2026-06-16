<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Academic;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Academic');
    }

    public function view(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('View:Academic');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Academic');
    }

    public function update(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('Update:Academic');
    }

    public function delete(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('Delete:Academic');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Academic');
    }

    public function restore(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('Restore:Academic');
    }

    public function forceDelete(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('ForceDelete:Academic');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Academic');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Academic');
    }

    public function replicate(AuthUser $authUser, Academic $academic): bool
    {
        return $authUser->can('Replicate:Academic');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Academic');
    }

}