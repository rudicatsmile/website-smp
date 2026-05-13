<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alumni\Pages;

use App\Filament\Resources\Alumni\TracerStudyResource;
use App\Models\TracerStudy;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTracerStudies extends ListRecords
{
    protected static string $resource = TracerStudyResource::class;

    public function getTabs(): array
    {
        return [
            'pending' => Tab::make('Belum Diproses')
                ->badge(TracerStudy::where('is_processed', false)->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn ($query) => $query->where('is_processed', false)),

            'processed' => Tab::make('Sudah Diproses')
                ->badge(TracerStudy::where('is_processed', true)->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn ($query) => $query->where('is_processed', true)),

            'all' => Tab::make('Semua'),
        ];
    }
}
