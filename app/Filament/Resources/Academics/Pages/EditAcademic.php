<?php

namespace App\Filament\Resources\Academics\Pages;

use App\Filament\Resources\Academics\AcademicResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAcademic extends EditRecord
{
    protected static string $resource = AcademicResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
