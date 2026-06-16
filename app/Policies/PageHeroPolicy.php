<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\PageHero;
use Illuminate\Auth\Access\HandlesAuthorization;

class PageHeroPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PageHero');
    }

    public function view(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('View:PageHero');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PageHero');
    }

    public function update(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('Update:PageHero');
    }

    public function delete(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('Delete:PageHero');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:PageHero');
    }

    public function restore(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('Restore:PageHero');
    }

    public function forceDelete(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('ForceDelete:PageHero');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PageHero');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PageHero');
    }

    public function replicate(AuthUser $authUser, PageHero $pageHero): bool
    {
        return $authUser->can('Replicate:PageHero');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PageHero');
    }

}