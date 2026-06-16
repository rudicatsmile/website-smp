<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LearningMethod;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningMethodPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LearningMethod');
    }

    public function view(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('View:LearningMethod');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LearningMethod');
    }

    public function update(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('Update:LearningMethod');
    }

    public function delete(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('Delete:LearningMethod');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LearningMethod');
    }

    public function restore(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('Restore:LearningMethod');
    }

    public function forceDelete(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('ForceDelete:LearningMethod');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LearningMethod');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LearningMethod');
    }

    public function replicate(AuthUser $authUser, LearningMethod $learningMethod): bool
    {
        return $authUser->can('Replicate:LearningMethod');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LearningMethod');
    }

}