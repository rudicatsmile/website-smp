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

class AttendanceReportExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle
{
    private const STATUS_LABELS = [
        'hadir'     => 'H',
        'sakit'     => 'S',
        'izin'      => 'I',
        'alpa'      => 'A',
        'terlambat' => 'T',
    ];

    public function __construct(
        private readonly array  $reportData,
        private readonly string $dateFrom,
        private readonly string $dateTo,
    ) {}

    public function title(): string
    {
        return 'Absensi';
    }

    public function array(): array
    {
        $rd    = $this->reportData;
        $dates = $rd['dates'];
        $rows  = $rd['rows'];
        $class = $rd['class'];

        $periodLabel = Carbon::parse($this->dateFrom)->isoFormat('D MMMM Y')
            . ' – '
            . Carbon::parse($this->dateTo)->isoFormat('D MMMM Y');

        // Title rows
        $result = [
            ['LAPORAN ABSENSI SISWA'],
            [$class?->name ?? ''],
            ['Periode: ' . $periodLabel],
            [],
        ];

        // Header row: No | Nama | NIS | [dates...] | S | I | A | Hadir | %
        $header = ['No', 'Nama', 'NIS'];
        foreach ($dates as $date) {
            $d = Carbon::parse($date);
            $header[] = $d->format('d/m') . "\n" . $d->isoFormat('dd');
        }
        $header[] = 'Sakit';
        $header[] = 'Izin';
        $header[] = 'Alpa';
        $header[] = 'Hadir';
        $header[] = '%';

        $result[] = $header;

        // Data rows
        foreach ($rows as $row) {
            $line = [
                $row['no'],
                $row['student']->name,
                $row['student']->nis ?? '',
            ];
            foreach ($dates as $date) {
                $rec    = $row['daily']->get($date);
                $status = $rec?->status;
                $line[] = $status ? (self::STATUS_LABELS[$status] ?? $status) : '';
            }
            $line[] = $row['sakit'] ?: '';
            $line[] = $row['izin'] ?: '';
            $line[] = $row['alpa'] ?: '';
            $line[] = $row['hadir'];
            $line[] = $row['persen'] . '%';

            $result[] = $line;
        }

        return $result;
    }

    public function styles(Worksheet $sheet): array
    {
        $dates      = $this->reportData['dates'];
        $totalCols  = 3 + count($dates) + 5; // No+Nama+NIS + dates + S+I+A+Hadir+%
        $dataRows   = count($this->reportData['rows']);
        $headerRow  = 5;
        $lastRow    = $headerRow + $dataRows;
        $lastCol    = $sheet->getCellByColumnAndRow($totalCols, 1)->getColumn();

        // Title rows: merge & bold
        foreach ([1, 2, 3] as $r) {
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
        }

        // Header row background
        $sheet->getStyle("A{$headerRow}:{$lastCol}{$headerRow}")->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1a5276']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'wrapText' => true],
        ]);

        // Title row styles
        $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(13);
        $sheet->getStyle("A2")->getFont()->setBold(true)->setSize(11);

        // Summary header columns: colour coding
        $summaryStart = 4 + count($dates);
        $colors = ['F9E79F', 'AED6F1', 'F1948A', 'ABEBC6', 'A9DFBF'];
        foreach (range(0, 4) as $i) {
            $col = $sheet->getCellByColumnAndRow($summaryStart + $i, $headerRow)->getColumn();
            $sheet->getStyle("{$col}{$headerRow}")->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB($colors[$i]);
            $sheet->getStyle("{$col}{$headerRow}")->getFont()->setColor((new \PhpOffice\PhpSpreadsheet\Style\Color())->setRGB('000000'));
        }

        // All data cells: border + center for date/summary columns
        if ($dataRows > 0) {
            $sheet->getStyle("A{$headerRow}:{$lastCol}{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']],
                ],
            ]);

            // Center date columns
            $dateStart = $sheet->getCellByColumnAndRow(4, $headerRow)->getColumn();
            $sheet->getStyle("{$dateStart}{$headerRow}:{$lastCol}{$lastRow}")
                ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        // Freeze pane after NIS column
        $sheet->freezePane('D' . ($headerRow + 1));

        return [];
    }
}
