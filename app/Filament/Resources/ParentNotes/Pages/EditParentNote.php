<?php

declare(strict_types=1);

namespace App\Filament\Resources\ParentNotes\Pages;

use App\Filament\Resources\ParentNotes\ParentNoteResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditParentNote extends EditRecord
{
    protected static string $resource = ParentNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
