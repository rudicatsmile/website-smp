<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ElectionLiveWidget;
use Filament\Pages\Page;

class ElectionLiveResults extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-pie';
    protected static string|\UnitEnum|null $navigationGroup = 'Election';
    protected static ?string $title = 'Live Hasil Suara';
    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.election-live-results';

    protected function getHeaderWidgets(): array
    {
        return [
            ElectionLiveWidget::class,
        ];
    }
}
