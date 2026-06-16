<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CurriculumPlan;
use Illuminate\Auth\Access\HandlesAuthorization;

class CurriculumPlanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CurriculumPlan');
    }

    public function view(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('View:CurriculumPlan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CurriculumPlan');
    }

    public function update(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('Update:CurriculumPlan');
    }

    public function delete(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('Delete:CurriculumPlan');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CurriculumPlan');
    }

    public function restore(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('Restore:CurriculumPlan');
    }

    public function forceDelete(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('ForceDelete:CurriculumPlan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CurriculumPlan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CurriculumPlan');
    }

    public function replicate(AuthUser $authUser, CurriculumPlan $curriculumPlan): bool
    {
        return $authUser->can('Replicate:CurriculumPlan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CurriculumPlan');
    }

}