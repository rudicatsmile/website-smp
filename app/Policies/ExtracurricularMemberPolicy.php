<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ExtracurricularMember;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtracurricularMemberPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExtracurricularMember');
    }

    public function view(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('View:ExtracurricularMember');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExtracurricularMember');
    }

    public function update(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('Update:ExtracurricularMember');
    }

    public function delete(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('Delete:ExtracurricularMember');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ExtracurricularMember');
    }

    public function restore(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('Restore:ExtracurricularMember');
    }

    public function forceDelete(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('ForceDelete:ExtracurricularMember');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExtracurricularMember');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExtracurricularMember');
    }

    public function replicate(AuthUser $authUser, ExtracurricularMember $extracurricularMember): bool
    {
        return $authUser->can('Replicate:ExtracurricularMember');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExtracurricularMember');
    }

}