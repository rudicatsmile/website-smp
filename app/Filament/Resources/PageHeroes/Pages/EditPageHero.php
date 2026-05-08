<?php

namespace App\Filament\Resources\PageHeroes\Pages;

use App\Filament\Resources\PageHeroes\PageHeroResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPageHero extends EditRecord
{
    protected static string $resource = PageHeroResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
