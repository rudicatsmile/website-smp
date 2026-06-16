<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StaffCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaffCategoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StaffCategory');
    }

    public function view(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('View:StaffCategory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StaffCategory');
    }

    public function update(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('Update:StaffCategory');
    }

    public function delete(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('Delete:StaffCategory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:StaffCategory');
    }

    public function restore(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('Restore:StaffCategory');
    }

    public function forceDelete(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('ForceDelete:StaffCategory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StaffCategory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StaffCategory');
    }

    public function replicate(AuthUser $authUser, StaffCategory $staffCategory): bool
    {
        return $authUser->can('Replicate:StaffCategory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StaffCategory');
    }

}