<?php

declare(strict_types=1);

namespace App\Filament\Resources\KkoLevels\Pages;

use App\Filament\Resources\KkoLevels\KkoLevelResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKkoLevel extends CreateRecord
{
    protected static string $resource = KkoLevelResource::class;
}
