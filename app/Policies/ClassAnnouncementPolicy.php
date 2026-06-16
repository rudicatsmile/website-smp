<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClassAnnouncement;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassAnnouncementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClassAnnouncement');
    }

    public function view(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('View:ClassAnnouncement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClassAnnouncement');
    }

    public function update(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('Update:ClassAnnouncement');
    }

    public function delete(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('Delete:ClassAnnouncement');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ClassAnnouncement');
    }

    public function restore(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('Restore:ClassAnnouncement');
    }

    public function forceDelete(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('ForceDelete:ClassAnnouncement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClassAnnouncement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClassAnnouncement');
    }

    public function replicate(AuthUser $authUser, ClassAnnouncement $classAnnouncement): bool
    {
        return $authUser->can('Replicate:ClassAnnouncement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClassAnnouncement');
    }

}