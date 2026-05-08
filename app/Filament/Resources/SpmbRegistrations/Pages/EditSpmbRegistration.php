<?php

namespace App\Filament\Resources\SpmbRegistrations\Pages;

use App\Filament\Resources\SpmbRegistrations\SpmbRegistrationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditSpmbRegistration extends EditRecord
{
    protected static string $resource = SpmbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
