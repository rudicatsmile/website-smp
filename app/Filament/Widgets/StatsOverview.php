<?php

namespace App\Filament\Widgets;

use App\Models\News;
use App\Models\Download;
use App\Models\Announcement;
use App\Models\ContactMessage;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Berita', News::count())
                ->description('Total berita yang dipublikasikan')
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('primary'),
            Stat::make('Unduhan', Download::count())
                ->description('Total file yang tersedia')
                ->descriptionIcon('heroicon-o-document-arrow-down')
                ->color('success'),
            Stat::make('Pengumuman', Announcement::active()->count())
                ->description('Pengumuman aktif saat ini')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('warning'),
            Stat::make('Pesan Masuk', ContactMessage::count())
                ->description('Total pesan dari pengunjung')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('danger'),
        ];
    }
}
