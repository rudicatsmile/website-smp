<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassMaterials\Pages;

use App\Filament\Resources\ClassMaterials\ClassMaterialResource;
use Filament\Resources\Pages\CreateRecord;

class CreateClassMaterial extends CreateRecord
{
    protected static string $resource = ClassMaterialResource::class;
}
