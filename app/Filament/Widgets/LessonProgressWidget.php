<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use App\Models\LessonSession;
use App\Models\SchoolClass;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;

class LessonProgressWidget extends ChartWidget
{
    protected ?string $heading = 'Penyelesaian Materi Bulan Ini';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public ?string $filter = 'all';

    protected function getFilters(): ?array
    {
        $classes = SchoolClass::active()->ordered()->pluck('name', 'id')->toArray();
        return ['all' => 'Semua Kelas'] + $classes;
    }

    protected function getData(): array
    {
        $cacheKey = 'widget_lesson_progress_' . ($this->filter ?? 'all') . '_' . now()->format('Ym');
        return Cache::remember($cacheKey, 300, function () {
            $startOfMonth = now()->startOfMonth();
            $endOfMonth = now()->endOfMonth();

            $query = LessonSession::query()
                ->whereBetween('session_date', [$startOfMonth, $endOfMonth]);

            if ($this->filter && $this->filter !== 'all') {
                $query->where('school_class_id', $this->filter);
            }

            $total = (int) $query->count();
            $completed = (int) (clone $query)->where('status', 'completed')->count();
            $ongoing = (int) (clone $query)->whereIn('status', ['published', 'ongoing'])->count();
            $cancelled = (int) (clone $query)->where('status', 'cancelled')->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Sesi',
                        'data' => [$completed, $ongoing, $cancelled],
                        'backgroundColor' => ['#10b981', '#f59e0b', '#ef4444'],
                    ],
                ],
                'labels' => ['Selesai', 'Akan Datang / Berlangsung', 'Dibatalkan'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }
}
