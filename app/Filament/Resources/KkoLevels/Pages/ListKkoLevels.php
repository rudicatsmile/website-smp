<?php

declare(strict_types=1);

namespace App\Filament\Resources\KkoLevels\Pages;

use App\Filament\Resources\KkoLevels\KkoLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKkoLevels extends ListRecords
{
    protected static string $resource = KkoLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
