<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TahfidzClass;
use Illuminate\Auth\Access\HandlesAuthorization;

class TahfidzClassPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TahfidzClass');
    }

    public function view(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('View:TahfidzClass');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TahfidzClass');
    }

    public function update(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('Update:TahfidzClass');
    }

    public function delete(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('Delete:TahfidzClass');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TahfidzClass');
    }

    public function restore(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('Restore:TahfidzClass');
    }

    public function forceDelete(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('ForceDelete:TahfidzClass');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TahfidzClass');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TahfidzClass');
    }

    public function replicate(AuthUser $authUser, TahfidzClass $tahfidzClass): bool
    {
        return $authUser->can('Replicate:TahfidzClass');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TahfidzClass');
    }

}