<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LessonSession;
use Illuminate\Auth\Access\HandlesAuthorization;

class LessonSessionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LessonSession');
    }

    public function view(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('View:LessonSession');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LessonSession');
    }

    public function update(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('Update:LessonSession');
    }

    public function delete(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('Delete:LessonSession');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LessonSession');
    }

    public function restore(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('Restore:LessonSession');
    }

    public function forceDelete(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('ForceDelete:LessonSession');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LessonSession');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LessonSession');
    }

    public function replicate(AuthUser $authUser, LessonSession $lessonSession): bool
    {
        return $authUser->can('Replicate:LessonSession');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LessonSession');
    }

}