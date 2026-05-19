<?php

declare(strict_types=1);

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class JurnalMengajarExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    public function __construct(
        private readonly array  $reportData,
        private readonly string $dateFrom,
        private readonly string $dateTo,
    ) {}

    public function title(): string
    {
        return 'Jurnal Mengajar';
    }

    public function array(): array
    {
        $rows = $this->reportData['rows'];
        $meta = $this->reportData['meta'];

        $periodLabel = Carbon::parse($this->dateFrom)->isoFormat('D MMMM Y')
            . ' – '
            . Carbon::parse($this->dateTo)->isoFormat('D MMMM Y');

        $subtitle = 'Jurnal Mengajar Pendidik';
        if ($meta['class'])         $subtitle .= ' — Kelas ' . $meta['class']->name;
        if ($meta['subject'])       $subtitle .= ' — ' . $meta['subject']->name;
        if ($meta['academic_year']) $subtitle .= ' — TA ' . $meta['academic_year'];

        $result = [
            ['JURNAL MENGAJAR PENDIDIK'],
            [$subtitle],
            ['Periode: ' . $periodLabel],
            [],
            ['No', 'Hari & Tanggal', 'Kelas', 'Mata Pelajaran', 'Minggu Pertemuan Ke', 'Bahasan Materi', 'Tujuan Pembelajaran', 'Jumlah Siswa Hadir', 'Keterangan'],
        ];

        foreach ($rows as $row) {
            $result[] = [
                $row['no'],
                $row['date_label'],
                $row['class_name'],
                $row['subject_name'],
                $row['week_number'] ?? '',
                $row['topic'],
                $row['session']->learning_objectives ?? '',
                $row['hadir'],
                $row['notes'] ?? '',
            ];
        }

        // Footer total
        $result[] = [];
        $result[] = ['', '', '', '', '', '', 'TOTAL HADIR', collect($rows)->sum('hadir'), ''];

        return $result;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = 5 + count($this->reportData['rows']);

        // Title merges
        foreach ([1, 2, 3] as $r) {
            $sheet->mergeCells("A{$r}:I{$r}");
        }

        // Title styles
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(11);
        $sheet->getStyle('A3')->getFont()->setSize(10);

        // Header row (row 5)
        $sheet->getStyle('A5:I5')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0f4c81']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
        ]);
        $sheet->getRowDimension(5)->setRowHeight(30);

        // Data rows border
        if ($lastRow >= 6) {
            $sheet->getStyle("A5:I{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'C9D9E8']],
                ],
            ]);

            // Alternate row colors
            for ($r = 6; $r <= $lastRow; $r++) {
                if ($r % 2 === 0) {
                    $sheet->getStyle("A{$r}:I{$r}")->getFill()
                        ->setFillType(Fill::FILL_SOLID)
                        ->getStartColor()->setRGB('F8FAFC');
                }
            }
        }

        // Hadir column (H) highlight
        if ($lastRow >= 6) {
            $sheet->getStyle("H6:H{$lastRow}")->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'font'      => ['bold' => true],
            ]);
        }

        // Week number column (E) center
        $sheet->getStyle("E5:E{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A5:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Text wrap for long columns
        $sheet->getStyle("F5:G{$lastRow}")->getAlignment()->setWrapText(true);
        $sheet->getStyle("I5:I{$lastRow}")->getAlignment()->setWrapText(true);

        // Footer row bold
        $footerRow = $lastRow + 2;
        $sheet->getStyle("G{$footerRow}:H{$footerRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'DBEAFE']],
        ]);

        // Col widths
        $sheet->getColumnDimension('B')->setWidth(28);
        $sheet->getColumnDimension('F')->setWidth(35);
        $sheet->getColumnDimension('G')->setWidth(35);
        $sheet->getColumnDimension('I')->setWidth(30);

        $sheet->freezePane('A6');

        return [];
    }
}
