<?php

namespace App\Filament\Resources\SchoolEvents\Pages;

use App\Filament\Resources\SchoolEvents\SchoolEventResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSchoolEvent extends EditRecord
{
    protected static string $resource = SchoolEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
