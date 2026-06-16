<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AssessmentType;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssessmentTypePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AssessmentType');
    }

    public function view(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('View:AssessmentType');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AssessmentType');
    }

    public function update(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('Update:AssessmentType');
    }

    public function delete(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('Delete:AssessmentType');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AssessmentType');
    }

    public function restore(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('Restore:AssessmentType');
    }

    public function forceDelete(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('ForceDelete:AssessmentType');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AssessmentType');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AssessmentType');
    }

    public function replicate(AuthUser $authUser, AssessmentType $assessmentType): bool
    {
        return $authUser->can('Replicate:AssessmentType');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AssessmentType');
    }

}