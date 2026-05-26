<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzClasses\Pages;

use App\Filament\Resources\TahfidzClasses\TahfidzClassResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTahfidzClass extends EditRecord
{
    protected static string $resource = TahfidzClassResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
