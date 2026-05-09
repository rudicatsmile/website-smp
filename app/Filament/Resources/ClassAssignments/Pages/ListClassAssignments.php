<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAssignments\Pages;

use App\Filament\Resources\ClassAssignments\ClassAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassAssignments extends ListRecords
{
    protected static string $resource = ClassAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
