<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\InternalAnnouncement;
use Illuminate\Auth\Access\HandlesAuthorization;

class InternalAnnouncementPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:InternalAnnouncement');
    }

    public function view(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('View:InternalAnnouncement');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:InternalAnnouncement');
    }

    public function update(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('Update:InternalAnnouncement');
    }

    public function delete(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('Delete:InternalAnnouncement');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:InternalAnnouncement');
    }

    public function restore(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('Restore:InternalAnnouncement');
    }

    public function forceDelete(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('ForceDelete:InternalAnnouncement');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:InternalAnnouncement');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:InternalAnnouncement');
    }

    public function replicate(AuthUser $authUser, InternalAnnouncement $internalAnnouncement): bool
    {
        return $authUser->can('Replicate:InternalAnnouncement');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:InternalAnnouncement');
    }

}