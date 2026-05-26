<?php

declare(strict_types=1);

namespace App\Filament\Resources\KkoLevels\Pages;

use App\Filament\Resources\KkoLevels\KkoLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKkoLevel extends EditRecord
{
    protected static string $resource = KkoLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
