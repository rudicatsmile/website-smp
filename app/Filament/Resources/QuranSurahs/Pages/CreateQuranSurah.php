<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuranSurahs\Pages;

use App\Filament\Resources\QuranSurahs\QuranSurahResource;
use Filament\Resources\Pages\CreateRecord;

class CreateQuranSurah extends CreateRecord
{
    protected static string $resource = QuranSurahResource::class;
}
