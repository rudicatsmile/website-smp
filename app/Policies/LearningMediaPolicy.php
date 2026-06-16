<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LearningMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningMediaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LearningMedia');
    }

    public function view(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('View:LearningMedia');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LearningMedia');
    }

    public function update(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('Update:LearningMedia');
    }

    public function delete(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('Delete:LearningMedia');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LearningMedia');
    }

    public function restore(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('Restore:LearningMedia');
    }

    public function forceDelete(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('ForceDelete:LearningMedia');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LearningMedia');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LearningMedia');
    }

    public function replicate(AuthUser $authUser, LearningMedia $learningMedia): bool
    {
        return $authUser->can('Replicate:LearningMedia');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LearningMedia');
    }

}