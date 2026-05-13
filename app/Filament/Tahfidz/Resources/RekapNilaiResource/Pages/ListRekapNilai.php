<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Resources\RekapNilaiResource\Pages;

use App\Filament\Tahfidz\Resources\RekapNilaiResource;
use Filament\Resources\Pages\ListRecords;

class ListRekapNilai extends ListRecords
{
    protected static string $resource = RekapNilaiResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
