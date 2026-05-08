<?php

namespace App\Filament\Resources\SpmbPeriods\Pages;

use App\Filament\Resources\SpmbPeriods\SpmbPeriodResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSpmbPeriod extends EditRecord
{
    protected static string $resource = SpmbPeriodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
