<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\CounselingTicket;
use Illuminate\Auth\Access\HandlesAuthorization;

class CounselingTicketPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CounselingTicket');
    }

    public function view(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('View:CounselingTicket');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CounselingTicket');
    }

    public function update(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('Update:CounselingTicket');
    }

    public function delete(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('Delete:CounselingTicket');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CounselingTicket');
    }

    public function restore(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('Restore:CounselingTicket');
    }

    public function forceDelete(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('ForceDelete:CounselingTicket');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CounselingTicket');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CounselingTicket');
    }

    public function replicate(AuthUser $authUser, CounselingTicket $counselingTicket): bool
    {
        return $authUser->can('Replicate:CounselingTicket');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CounselingTicket');
    }

}