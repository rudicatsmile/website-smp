<?php

namespace App\Filament\Resources\SpmbRegistrations\Pages;

use App\Filament\Resources\SpmbRegistrations\SpmbRegistrationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpmbRegistrations extends ListRecords
{
    protected static string $resource = SpmbRegistrationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
