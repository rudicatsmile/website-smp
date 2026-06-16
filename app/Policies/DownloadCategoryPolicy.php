<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DownloadCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class DownloadCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DownloadCategory');
    }

    public function view(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('View:DownloadCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DownloadCategory');
    }

    public function update(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('Update:DownloadCategory');
    }

    public function delete(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('Delete:DownloadCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DownloadCategory');
    }

    public function restore(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('Restore:DownloadCategory');
    }

    public function forceDelete(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('ForceDelete:DownloadCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DownloadCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DownloadCategory');
    }

    public function replicate(AuthUser $authUser, DownloadCategory $downloadCategory): bool
    {
        return $authUser->can('Replicate:DownloadCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DownloadCategory');
    }

}