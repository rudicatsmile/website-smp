<?php

namespace App\Filament\Resources\SpmbPeriods\Pages;

use App\Filament\Resources\SpmbPeriods\SpmbPeriodResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSpmbPeriods extends ListRecords
{
    protected static string $resource = SpmbPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
