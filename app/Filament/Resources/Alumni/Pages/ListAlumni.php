<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alumni\Pages;

use App\Filament\Resources\Alumni\AlumniResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAlumni extends ListRecords
{
    protected static string $resource = AlumniResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
