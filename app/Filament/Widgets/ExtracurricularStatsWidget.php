<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\Extracurricular;
use App\Models\ExtracurricularMember;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExtracurricularStatsWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected function getStats(): array
    {
        return [
            Stat::make('Ekskul Aktif', Extracurricular::active()->count())
                ->icon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Anggota Diterima', ExtracurricularMember::approved()->count())
                ->icon('heroicon-o-check-circle')
                ->color('info'),

            Stat::make('Pendaftaran Pending', ExtracurricularMember::pending()->count())
                ->icon('heroicon-o-clock')
                ->color('warning'),
        ];
    }
}
