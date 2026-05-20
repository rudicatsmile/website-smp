<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\ExamSession;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanPenilaianExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Penilaian Peserta Didik';
    }

    public function array(): array
    {
        $tpSessions = $this->data['tpSessions'] ?? collect();
        $examTypes  = $this->data['examTypes']  ?? collect();
        $rows       = $this->data['rows']       ?? [];
        $class      = $this->data['class']      ?? null;
        $subject    = $this->data['subject']    ?? null;
        $typeLabels = ExamSession::TYPES;

        $sheets = [];

        // Title
        $sheets[] = ['LAPORAN PENILAIAN PESERTA DIDIK'];
        $sheets[] = [
            'Kelas: ' . ($class?->name ?? '—'),
            '',
            'Mata Pelajaran: ' . ($subject?->name ?? '—'),
        ];
        $sheets[] = [];

        // Header row 1 — group labels
        $header1 = ['No', 'NIS', 'Nama Peserta Didik'];
        foreach ($tpSessions as $idx => $s) {
            $header1[] = 'TP-' . ($idx + 1);
        }
        foreach ($examTypes as $type) {
            $header1[] = $typeLabels[$type] ?? strtoupper($type);
        }
        $header1[] = 'Sem 1';
        $header1[] = 'Sem 2';
        $sheets[] = $header1;

        // Data rows
        foreach ($rows as $row) {
            $line = [
                $row['no'],
                $row['student']->nis ?? '',
                $row['student']->name,
            ];
            foreach ($tpSessions as $session) {
                $val = $row['tpScores'][$session->id] ?? null;
                $line[] = $val !== null ? $val : '';
            }
            foreach ($examTypes as $type) {
                $val = $row['examScoresByType'][$type] ?? null;
                $line[] = $val !== null ? $val : '';
            }
            $line[] = $row['sem1'] ?? '';
            $line[] = $row['sem2'] ?? '';
            $sheets[] = $line;
        }

        // Average row
        $avgRow = ['', '', 'Rata-rata Kelas'];
        foreach ($tpSessions as $session) {
            $vals = collect($rows)->pluck("tpScores.{$session->id}")->filter(fn ($v) => $v !== null);
            $avgRow[] = $vals->isNotEmpty() ? round($vals->avg(), 1) : '';
        }
        foreach ($examTypes as $type) {
            $vals = collect($rows)->pluck("examScoresByType.{$type}")->filter(fn ($v) => $v !== null);
            $avgRow[] = $vals->isNotEmpty() ? round($vals->avg(), 1) : '';
        }
        $s1vals = collect($rows)->pluck('sem1')->filter(fn ($v) => $v !== null);
        $s2vals = collect($rows)->pluck('sem2')->filter(fn ($v) => $v !== null);
        $avgRow[] = $s1vals->isNotEmpty() ? round($s1vals->avg(), 1) : '';
        $avgRow[] = $s2vals->isNotEmpty() ? round($s2vals->avg(), 1) : '';
        $sheets[] = $avgRow;

        return $sheets;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        // Title style
        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Header row (row 4)
        $sheet->getStyle('A4:' . $lastCol . '4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1E3A8A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // All data borders
        $sheet->getStyle('A4:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
            ],
        ]);

        // Last row (average) bold
        $sheet->getStyle('A' . $lastRow . ':' . $lastCol . $lastRow)->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F0FDF4']],
        ]);

        return [];
    }
}
