<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassMaterials\Pages;

use App\Filament\Resources\ClassMaterials\ClassMaterialResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassMaterials extends ListRecords
{
    protected static string $resource = ClassMaterialResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
