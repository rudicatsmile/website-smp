<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LearningModel;
use Illuminate\Auth\Access\HandlesAuthorization;

class LearningModelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LearningModel');
    }

    public function view(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('View:LearningModel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LearningModel');
    }

    public function update(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('Update:LearningModel');
    }

    public function delete(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('Delete:LearningModel');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LearningModel');
    }

    public function restore(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('Restore:LearningModel');
    }

    public function forceDelete(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('ForceDelete:LearningModel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LearningModel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LearningModel');
    }

    public function replicate(AuthUser $authUser, LearningModel $learningModel): bool
    {
        return $authUser->can('Replicate:LearningModel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LearningModel');
    }

}