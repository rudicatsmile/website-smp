<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SpmbPeriod;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpmbPeriodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SpmbPeriod');
    }

    public function view(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('View:SpmbPeriod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SpmbPeriod');
    }

    public function update(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('Update:SpmbPeriod');
    }

    public function delete(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('Delete:SpmbPeriod');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SpmbPeriod');
    }

    public function restore(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('Restore:SpmbPeriod');
    }

    public function forceDelete(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('ForceDelete:SpmbPeriod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SpmbPeriod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SpmbPeriod');
    }

    public function replicate(AuthUser $authUser, SpmbPeriod $spmbPeriod): bool
    {
        return $authUser->can('Replicate:SpmbPeriod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SpmbPeriod');
    }

}