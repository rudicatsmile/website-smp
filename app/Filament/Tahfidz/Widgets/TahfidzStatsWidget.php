<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Widgets;

use App\Models\TahfidzGrade;
use App\Models\TahfidzParticipant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TahfidzStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSiswa    = TahfidzParticipant::active()->count();
        $totalSurah    = TahfidzGrade::count();
        $avgProgres    = 0;

        if ($totalSiswa > 0) {
            $participants = TahfidzParticipant::active()->with('grades')->get();
            $avgProgres   = $participants->avg(function ($p) {
                if ($p->surah_target <= 0) {
                    return 0;
                }
                return ($p->grades->count() / $p->surah_target) * 100;
            });
        }

        return [
            Stat::make('Total Siswa Aktif', $totalSiswa)
                ->description('Peserta terdaftar')
                ->icon('heroicon-o-users')
                ->color('indigo'),

            Stat::make('Total Surah Selesai', $totalSurah)
                ->description('Seluruh peserta')
                ->icon('heroicon-o-book-open')
                ->color('emerald'),

            Stat::make('Rata-Rata Progres', round($avgProgres, 1) . '%')
                ->description('Progres seluruh peserta')
                ->icon('heroicon-o-chart-bar')
                ->color('amber'),
        ];
    }
}
