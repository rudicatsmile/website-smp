<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\StudentViolation;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentViolationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:StudentViolation');
    }

    public function view(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('View:StudentViolation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:StudentViolation');
    }

    public function update(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('Update:StudentViolation');
    }

    public function delete(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('Delete:StudentViolation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:StudentViolation');
    }

    public function restore(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('Restore:StudentViolation');
    }

    public function forceDelete(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('ForceDelete:StudentViolation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:StudentViolation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:StudentViolation');
    }

    public function replicate(AuthUser $authUser, StudentViolation $studentViolation): bool
    {
        return $authUser->can('Replicate:StudentViolation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:StudentViolation');
    }

}