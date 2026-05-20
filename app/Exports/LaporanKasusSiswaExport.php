<?php

declare(strict_types=1);

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKasusSiswaExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    private array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Kasus Peserta Didik';
    }

    public function array(): array
    {
        $rows    = $this->data['rows']    ?? [];
        $class   = $this->data['class']   ?? null;
        $subject = $this->data['subject'] ?? null;

        $sheets = [];

        $sheets[] = ['CATATAN KASUS PESERTA DIDIK SELAMA KEGIATAN BELAJAR'];
        $sheets[] = [
            'Kelas: ' . ($class?->name ?? '—'),
            '',
            'Mata Pelajaran: ' . ($subject?->name ?? '—'),
        ];
        $sheets[] = [];

        $sheets[] = ['No', 'Nama Peserta Didik', 'Tanggal', 'Kelas', 'Masalah / Kasus', 'S', 'TS', 'Tindak Lanjut'];

        foreach ($rows as $row) {
            $sheets[] = [
                $row['no'],
                $row['student']?->name ?? '—',
                $row['date'] ? \Carbon\Carbon::parse($row['date'])->format('d/m/Y') : '—',
                $row['class'],
                $row['problem'],
                $row['selesai']  ? '✓' : '',
                ! $row['selesai'] ? '✓' : '',
                $row['follow_up'] ?? '',
            ];
        }

        return $sheets;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastCol = $sheet->getHighestColumn();
        $lastRow = $sheet->getHighestRow();

        $sheet->mergeCells('A1:' . $lastCol . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => '7C2D12']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:' . $lastCol . '4')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '7C2D12']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $sheet->getStyle('A4:' . $lastCol . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'D1D5DB']],
            ],
        ]);

        // S column (F) — green
        $sheet->getStyle('F5:F' . $lastRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => '166534']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // TS column (G) — red
        $sheet->getStyle('G5:G' . $lastRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        return [];
    }
}
