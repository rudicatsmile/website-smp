<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

trait HidesFromEkskulRole
{
    public static function canViewAny(): bool
    {
        if (auth()->user()?->hasRole('guru_ekstrakurikuler')) {
            return false;
        }

        return parent::canViewAny();
    }
}
