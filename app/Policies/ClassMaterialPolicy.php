<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ClassMaterial;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClassMaterialPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ClassMaterial');
    }

    public function view(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('View:ClassMaterial');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ClassMaterial');
    }

    public function update(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('Update:ClassMaterial');
    }

    public function delete(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('Delete:ClassMaterial');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ClassMaterial');
    }

    public function restore(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('Restore:ClassMaterial');
    }

    public function forceDelete(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('ForceDelete:ClassMaterial');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ClassMaterial');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ClassMaterial');
    }

    public function replicate(AuthUser $authUser, ClassMaterial $classMaterial): bool
    {
        return $authUser->can('Replicate:ClassMaterial');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ClassMaterial');
    }

}