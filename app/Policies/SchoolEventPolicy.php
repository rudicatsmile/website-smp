<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SchoolEvent;
use Illuminate\Auth\Access\HandlesAuthorization;

class SchoolEventPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SchoolEvent');
    }

    public function view(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('View:SchoolEvent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SchoolEvent');
    }

    public function update(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('Update:SchoolEvent');
    }

    public function delete(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('Delete:SchoolEvent');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SchoolEvent');
    }

    public function restore(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('Restore:SchoolEvent');
    }

    public function forceDelete(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('ForceDelete:SchoolEvent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SchoolEvent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SchoolEvent');
    }

    public function replicate(AuthUser $authUser, SchoolEvent $schoolEvent): bool
    {
        return $authUser->can('Replicate:SchoolEvent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SchoolEvent');
    }

}