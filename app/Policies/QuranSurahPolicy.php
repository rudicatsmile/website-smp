<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\QuranSurah;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuranSurahPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:QuranSurah');
    }

    public function view(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('View:QuranSurah');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:QuranSurah');
    }

    public function update(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('Update:QuranSurah');
    }

    public function delete(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('Delete:QuranSurah');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:QuranSurah');
    }

    public function restore(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('Restore:QuranSurah');
    }

    public function forceDelete(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('ForceDelete:QuranSurah');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:QuranSurah');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:QuranSurah');
    }

    public function replicate(AuthUser $authUser, QuranSurah $quranSurah): bool
    {
        return $authUser->can('Replicate:QuranSurah');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:QuranSurah');
    }

}