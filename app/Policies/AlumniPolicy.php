<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Alumni;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlumniPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Alumni');
    }

    public function view(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('View:Alumni');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Alumni');
    }

    public function update(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('Update:Alumni');
    }

    public function delete(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('Delete:Alumni');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Alumni');
    }

    public function restore(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('Restore:Alumni');
    }

    public function forceDelete(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('ForceDelete:Alumni');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Alumni');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Alumni');
    }

    public function replicate(AuthUser $authUser, Alumni $alumni): bool
    {
        return $authUser->can('Replicate:Alumni');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Alumni');
    }

}