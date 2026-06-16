<?php

namespace App\Filament\Widgets;

use App\Models\Election;
use App\Models\ElectionCandidate;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ElectionLiveWidget extends ChartWidget
{
    protected ?string $pollingInterval = '5s';

    protected static bool $isDiscovered = false;

    public function getHeading(): string
    {
        return 'Live Hasil Perolehan Suara OSIS';
    }
    // Auto-refresh chart setiap 5 detik
    protected function getData(): array
    {
        // Ambil election yang aktif (bisa disesuaikan dengan logic lain, misal get data berdasarkan dropdown)
        $activeElection = Election::where('is_active', true)->first();

        if (! $activeElection) {
            return [
                'datasets' => [],
                'labels' => [],
            ];
        }

        // Ambil daftar kandidat beserta jumlah vote-nya
        $candidates = ElectionCandidate::withCount('votes')
            ->where('election_id', $activeElection->id)
            ->orderBy('candidate_number')
            ->get();

        $labels = [];
        $data = [];
        $backgroundColors = [
            'rgba(54, 162, 235, 0.8)', // Blue
            'rgba(255, 99, 132, 0.8)', // Red
            'rgba(255, 206, 86, 0.8)', // Yellow
            'rgba(75, 192, 192, 0.8)', // Green
            'rgba(153, 102, 255, 0.8)', // Purple
            'rgba(255, 159, 64, 0.8)', // Orange
        ];

        foreach ($candidates as $index => $candidate) {
            $labels[] = "No. {$candidate->candidate_number} - {$candidate->name}";
            $data[] = $candidate->votes_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Suara',
                    'data' => $data,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($data)),
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
