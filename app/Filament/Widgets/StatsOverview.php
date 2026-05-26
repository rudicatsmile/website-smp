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
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 3])
                ->extraAttributes([
                    'class' => 'animate-slide-in-up',
                ]),
            Stat::make('Unduhan', Download::count())
                ->description('Total file yang tersedia')
                ->descriptionIcon('heroicon-o-document-arrow-down')
                ->color('success')
                ->chart([3, 5, 4, 6, 7, 5, 6, 8])
                ->extraAttributes([
                    'class' => 'animate-slide-in-up',
                    'style' => 'animation-delay: 0.1s',
                ]),
            Stat::make('Pengumuman', Announcement::active()->count())
                ->description('Pengumuman aktif saat ini')
                ->descriptionIcon('heroicon-o-megaphone')
                ->color('warning')
                ->chart([2, 4, 3, 5, 4, 6, 5, 7])
                ->extraAttributes([
                    'class' => 'animate-slide-in-up',
                    'style' => 'animation-delay: 0.2s',
                ]),
            Stat::make('Pesan Masuk', ContactMessage::count())
                ->description('Total pesan dari pengunjung')
                ->descriptionIcon('heroicon-o-envelope')
                ->color('danger')
                ->chart([5, 6, 4, 7, 5, 8, 6, 9])
                ->extraAttributes([
                    'class' => 'animate-slide-in-up',
                    'style' => 'animation-delay: 0.3s',
                ]),
        ];
    }
}
