<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KkoLevel;
use Illuminate\Auth\Access\HandlesAuthorization;

class KkoLevelPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KkoLevel');
    }

    public function view(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('View:KkoLevel');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KkoLevel');
    }

    public function update(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('Update:KkoLevel');
    }

    public function delete(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('Delete:KkoLevel');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KkoLevel');
    }

    public function restore(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('Restore:KkoLevel');
    }

    public function forceDelete(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('ForceDelete:KkoLevel');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KkoLevel');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KkoLevel');
    }

    public function replicate(AuthUser $authUser, KkoLevel $kkoLevel): bool
    {
        return $authUser->can('Replicate:KkoLevel');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KkoLevel');
    }

}