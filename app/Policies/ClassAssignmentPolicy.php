<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClassAssignment;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassAssignmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClassAssignment');
    }

    public function view(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('View:ClassAssignment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClassAssignment');
    }

    public function update(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('Update:ClassAssignment');
    }

    public function delete(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('Delete:ClassAssignment');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ClassAssignment');
    }

    public function restore(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('Restore:ClassAssignment');
    }

    public function forceDelete(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('ForceDelete:ClassAssignment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClassAssignment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClassAssignment');
    }

    public function replicate(AuthUser $authUser, ClassAssignment $classAssignment): bool
    {
        return $authUser->can('Replicate:ClassAssignment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClassAssignment');
    }

}