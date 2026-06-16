<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TracerStudy;
use Illuminate\Auth\Access\HandlesAuthorization;

class TracerStudyPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TracerStudy');
    }

    public function view(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('View:TracerStudy');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TracerStudy');
    }

    public function update(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('Update:TracerStudy');
    }

    public function delete(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('Delete:TracerStudy');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TracerStudy');
    }

    public function restore(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('Restore:TracerStudy');
    }

    public function forceDelete(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('ForceDelete:TracerStudy');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TracerStudy');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TracerStudy');
    }

    public function replicate(AuthUser $authUser, TracerStudy $tracerStudy): bool
    {
        return $authUser->can('Replicate:TracerStudy');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TracerStudy');
    }

}