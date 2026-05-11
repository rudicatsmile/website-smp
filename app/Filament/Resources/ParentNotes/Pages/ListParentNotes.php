<?php

declare(strict_types=1);

namespace App\Filament\Resources\ParentNotes\Pages;

use App\Filament\Resources\ParentNotes\ParentNoteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListParentNotes extends ListRecords
{
    protected static string $resource = ParentNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
