<?php

declare(strict_types=1);

namespace App\Filament\Resources\TahfidzClasses\Pages;

use App\Filament\Resources\TahfidzClasses\TahfidzClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTahfidzClasses extends ListRecords
{
    protected static string $resource = TahfidzClassResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
