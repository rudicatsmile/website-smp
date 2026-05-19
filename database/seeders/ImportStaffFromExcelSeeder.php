<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StaffMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportStaffFromExcelSeeder extends Seeder
{
    private const EXCEL_PATH  = 'docs/tmp/guru.xlsx';
    private const DATA_START_ROW = 6;

    public function run(): void
    {
        $path = base_path(self::EXCEL_PATH);

        if (! file_exists($path)) {
            $this->command->error("File not found: {$path}");
            return;
        }

        $this->command->info('Loading Excel file...');
        $spreadsheet = IOFactory::load($path);
        $sheet       = $spreadsheet->getActiveSheet();

        $rows     = $sheet->getHighestRow();
        $imported = 0;
        $skipped  = 0;

        $this->command->info("Processing rows " . self::DATA_START_ROW . " – {$rows}...");

        // Col index → letter:
        // A=1 B=2 C=3 D=4 E=5 F=6 G=7 H=8 I=9 J=10 K=11 L=12 M=13 N=14 O=15
        // P=16 Q=17 R=18 S=19 T=20 U=21 V=22 W=23 X=24 Y=25 Z=26
        // AA=27 AB=28 AC=29 AD=30 AE=31 AF=32 AG=33 AH=34 AI=35 AJ=36
        // AK=37 AL=38 AM=39 AN=40 AO=41 AP=42 AQ=43 AR=44 AS=45 AT=46
        // AU=47 AV=48 AW=49 AX=50 AY=51

        DB::beginTransaction();
        try {
            for ($r = self::DATA_START_ROW; $r <= $rows; $r++) {
                $name = trim((string) $sheet->getCell("B{$r}")->getValue());

                if (empty($name)) {
                    $skipped++;
                    continue;
                }

                $data = $this->mapRow($sheet, $r);

                $updateColumns = array_keys(
                    array_diff_key($data, array_flip(['nuptk', 'slug', 'created_at']))
                );

                StaffMember::upsert([$data], ['nuptk'], $updateColumns);

                $imported++;
            }

            DB::commit();
            $this->command->info("Import complete: {$imported} records upserted, {$skipped} skipped.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function mapRow(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $r): array
    {
        $get = fn (string $col) => $sheet->getCell("{$col}{$r}")->getValue();

        $name  = trim((string) $get('B'));
        $nuptk = $this->nullableStr($get('C'));
        $slug  = $this->uniqueSlug($name, $nuptk ?? (string) $r);

        return [
            'name'                     => $name,
            'slug'                     => $slug,
            'nuptk'                    => $nuptk,                            // col 3
            'gender'                   => $this->mapGender($get('D')),       // col 4
            'birth_place'              => $this->nullableStr($get('E')),     // col 5
            'birth_date'               => $this->parseDate($get('F')),       // col 6
            'nip'                      => $this->nullableStr($get('G')),     // col 7

            // Kepegawaian
            'employment_status'        => $this->nullableStr($get('H')),     // col 8
            'ptk_type'                 => $this->nullableStr($get('I')),     // col 9
            'sk_cpns'                  => $this->nullableStr($get('V')),     // col 22
            'sk_cpns_date'             => $this->parseDate($get('W')),       // col 23
            'sk_appointment'           => $this->nullableStr($get('X')),     // col 24
            'joined_at'                => $this->parseDate($get('Y')),       // col 25 TMT Pengangkatan
            'appointing_agency'        => $this->nullableStr($get('Z')),     // col 26
            'rank_grade'               => $this->nullableStr($get('AA')),    // col 27
            'salary_source'            => $this->nullableStr($get('AB')),    // col 28
            'civil_servant_start_date' => $this->parseDate($get('AH')),     // col 34
            'nuks'                     => $this->nullableStr($get('AY')),    // col 51

            // Alamat
            'address'                  => $this->nullableStr($get('K')),     // col 11
            'rt'                       => $this->nullableStr($get('L')),     // col 12
            'rw'                       => $this->nullableStr($get('M')),     // col 13
            'dusun'                    => $this->nullableStr($get('N')),     // col 14
            'kelurahan'                => $this->nullableStr($get('O')),     // col 15
            'kecamatan'                => $this->nullableStr($get('P')),     // col 16
            'postal_code'              => $this->nullableStr($get('Q')),     // col 17
            'phone_home'               => $this->nullableStr($get('R')),     // col 18

            // Kontak
            'phone'                    => $this->nullableStr($get('S')),     // col 19 HP
            'email'                    => $this->nullableStr($get('T')),     // col 20
            'position'                 => $this->nullableStr($get('U')),     // col 21 Tugas Tambahan

            // Data Pribadi
            'religion'                 => $this->nullableStr($get('J')),     // col 10
            'nik'                      => $this->nullableStr($get('AS')),    // col 45
            'kk_number'                => $this->nullableStr($get('AT')),    // col 46
            'mother_name'              => $this->nullableStr($get('AC')),    // col 29
            'marital_status'           => $this->nullableStr($get('AD')),    // col 30
            'spouse_name'              => $this->nullableStr($get('AE')),    // col 31
            'spouse_nip'               => $this->nullableStr($get('AF')),    // col 32
            'spouse_occupation'        => $this->nullableStr($get('AG')),    // col 33
            'nationality'              => $this->nullableStr($get('AO')),    // col 41
            'npwp'                     => $this->nullableStr($get('AM')),    // col 39
            'taxpayer_name'            => $this->nullableStr($get('AN')),    // col 40

            // Dokumen
            'karpeg'                   => $this->nullableStr($get('AU')),    // col 47
            'karis_karsu'              => $this->nullableStr($get('AV')),    // col 48

            // Kompetensi
            'has_principal_license'    => $this->yesNo($get('AI')),         // col 35
            'has_supervision_training' => $this->yesNo($get('AJ')),         // col 36
            'braille_skill'            => $this->yesNo($get('AK')),         // col 37
            'sign_language_skill'      => $this->yesNo($get('AL')),         // col 38

            // Bank
            'bank_name'                => $this->nullableStr($get('AP')),    // col 42
            'bank_account_number'      => $this->nullableStr($get('AQ')),    // col 43
            'bank_account_name'        => $this->nullableStr($get('AR')),    // col 44

            // GPS
            'latitude'                 => $this->nullableCoord($get('AW'), -90, 90),    // col 49
            'longitude'                => $this->nullableCoord($get('AX'), -180, 180),  // col 50

            'is_active'                => true,
            'created_at'               => now(),
            'updated_at'               => now(),
        ];
    }

    private function nullableStr(mixed $v): ?string
    {
        $s = trim((string) $v);
        return $s === '' || $s === '0' ? null : $s;
    }

    private function yesNo(mixed $v): ?bool
    {
        if ($v === null || $v === '') return null;
        return strtolower(trim((string) $v)) === 'ya';
    }

    private function mapGender(mixed $v): ?string
    {
        return match (strtoupper(trim((string) $v))) {
            'L'     => 'L',
            'P'     => 'P',
            default => null,
        };
    }

    private function parseDate(mixed $v): ?string
    {
        if ($v === null || $v === '') return null;

        if (is_int($v) || is_float($v)) {
            try {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($v)->format('Y-m-d');
            } catch (\Throwable) {
                return null;
            }
        }

        $s = trim((string) $v);
        if ($s === '') return null;
        try {
            return (new \DateTime($s))->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function nullableCoord(mixed $v, float $min, float $max): ?float
    {
        if ($v === null || $v === '') return null;
        $f = (float) $v;
        if ($f === 0.0 || $f < $min || $f > $max) return null;
        return $f;
    }

    private function uniqueSlug(string $name, string $fallback): string
    {
        $base = Str::slug($name);
        if (empty($base)) {
            $base = Str::slug($fallback);
        }

        $existing = StaffMember::where('slug', 'like', "{$base}%")->pluck('slug')->toArray();
        if (! in_array($base, $existing)) {
            return $base;
        }

        return $base . '-' . Str::slug($fallback);
    }
}
