<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportStudentsFromExcelSeeder extends Seeder
{
    private const EXCEL_PATH = 'docs/tmp/sisws.xlsx';
    private const DATA_START_ROW = 7;

    public function run(): void
    {
        $path = base_path(self::EXCEL_PATH);

        if (! file_exists($path)) {
            $this->command->error("File not found: {$path}");
            return;
        }

        $this->command->info('Loading Excel file...');
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $this->command->info('Loading school classes...');
        $classMap = $this->buildClassMap();

        $rows      = $sheet->getHighestRow();
        $imported  = 0;
        $skipped   = 0;
        $errors    = 0;

        $this->command->info("Processing {$rows} rows (data starts row " . self::DATA_START_ROW . ")...");

        DB::beginTransaction();
        try {
            for ($r = self::DATA_START_ROW; $r <= $rows; $r++) {
                $name = trim((string) $sheet->getCell("B{$r}")->getValue());

                if (empty($name)) {
                    continue;
                }

                $nis = (string) $sheet->getCell("C{$r}")->getValue();
                if (empty($nis)) {
                    $skipped++;
                    continue;
                }

                $nis = trim($nis);

                $data = $this->mapRow($sheet, $r, $classMap);

                $updateColumns = array_keys(
                    array_diff_key($data, array_flip(['nis', 'slug', 'created_at']))
                );

                Student::upsert([$data], ['nis'], $updateColumns);

                $imported++;

                if ($imported % 50 === 0) {
                    $this->command->info("  {$imported} records processed...");
                }
            }

            DB::commit();
            $this->command->info("Import complete: {$imported} records upserted, {$skipped} skipped.");
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command->error('Import failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function mapRow(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, int $r, array $classMap): array
    {
        $get = fn (string $col) => $this->cellValue($sheet, $col, $r);

        // Col index → letter reference:
        // A=1 B=2 C=3 D=4 E=5 F=6 G=7 H=8 I=9 J=10 K=11 L=12 M=13 N=14 O=15
        // P=16 Q=17 R=18 S=19 T=20 U=21 V=22 W=23 X=24 Y=25 Z=26
        // AA=27 AB=28 AC=29 AD=30 AE=31 AF=32 AG=33 AH=34 AI=35 AJ=36
        // AK=37 AL=38 AM=39 AN=40 AO=41 AP=42 AQ=43 AR=44 AS=45 AT=46
        // AU=47 AV=48 AW=49 AX=50 AY=51 AZ=52
        // BA=53 BB=54 BC=55 BD=56 BE=57 BF=58 BG=59 BH=60 BI=61 BJ=62
        // BK=63 BL=64 BM=65 BN=66

        $name  = trim((string) $get('B'));   // col 2
        $nis   = trim((string) $get('C'));   // col 3 NIPD
        $slug  = $this->uniqueSlug($name, $nis);

        $classRaw      = trim((string) $get('AQ')); // col 43 Rombel
        $schoolClassId = $classMap[$classRaw] ?? null;

        return [
            'nis'                      => $nis,
            'nisn'                     => $this->nullableStr($get('E')),   // col 5
            'name'                     => $name,
            'slug'                     => $slug,
            'gender'                   => $this->mapGender($get('D')),     // col 4
            'birth_place'              => $this->nullableStr($get('F')),   // col 6
            'birth_date'               => $this->parseDate($get('G')),     // col 7
            'school_class_id'          => $schoolClassId,

            // Kependudukan
            'nik'                      => $this->nullableStr($get('H')),   // col 8
            'religion'                 => $this->nullableStr($get('I')),   // col 9
            'skhun'                    => $this->nullableStr($get('V')),   // col 22
            'birth_certificate_number' => $this->nullableStr($get('AX')), // col 50
            'kk_number'                => $this->nullableStr($get('BI')),  // col 61

            // Alamat
            'address'                  => $this->nullableStr($get('J')),   // col 10
            'rt'                       => $this->nullableStr($get('K')),   // col 11
            'rw'                       => $this->nullableStr($get('L')),   // col 12
            'dusun'                    => $this->nullableStr($get('M')),   // col 13
            'kelurahan'                => $this->nullableStr($get('N')),   // col 14
            'kecamatan'                => $this->nullableStr($get('O')),   // col 15
            'postal_code'              => $this->nullableStr($get('P')),   // col 16
            'living_with'              => $this->nullableStr($get('Q')),   // col 17
            'transportation'           => $this->nullableStr($get('R')),   // col 18
            'phone'                    => $this->nullableStr($get('S')),   // col 19 Telepon
            'parent_phone'             => $this->nullableStr($get('T')),   // col 20 HP
            'parent_email'             => $this->nullableStr($get('U')),   // col 21

            // Data Ayah
            'parent_name'              => $this->nullableStr($get('Y')),   // col 25
            'father_birth_year'        => $this->nullableInt($get('Z')),   // col 26
            'father_education'         => $this->nullableStr($get('AA')),  // col 27
            'father_occupation'        => $this->nullableStr($get('AB')),  // col 28
            'father_income'            => $this->nullableStr($get('AC')),  // col 29
            'father_nik'               => $this->nullableStr($get('AD')),  // col 30

            // Data Ibu
            'mother_name'              => $this->nullableStr($get('AE')),  // col 31
            'mother_birth_year'        => $this->nullableInt($get('AF')),  // col 32
            'mother_education'         => $this->nullableStr($get('AG')),  // col 33
            'mother_occupation'        => $this->nullableStr($get('AH')),  // col 34
            'mother_income'            => $this->nullableStr($get('AI')),  // col 35
            'mother_nik'               => $this->nullableStr($get('AJ')),  // col 36

            // Data Wali
            'guardian_name'            => $this->nullableStr($get('AK')),  // col 37
            'guardian_birth_year'      => $this->nullableInt($get('AL')),  // col 38
            'guardian_education'       => $this->nullableStr($get('AM')),  // col 39
            'guardian_occupation'      => $this->nullableStr($get('AN')),  // col 40
            'guardian_income'          => $this->nullableStr($get('AO')),  // col 41
            'guardian_nik'             => $this->nullableStr($get('AP')),  // col 42

            // Sekolah & Dokumen
            'un_number'                => $this->nullableStr($get('AR')),  // col 44
            'certificate_number'       => $this->nullableStr($get('AS')),  // col 45
            'previous_school'          => $this->nullableStr($get('BE')),  // col 57
            'child_order'              => $this->nullableInt($get('BF')),  // col 58

            // Bantuan Sosial
            'kps_recipient'            => $this->yesNo($get('W')),         // col 23
            'kps_number'               => $this->nullableStr($get('X')),   // col 24
            'kip_recipient'            => $this->yesNo($get('AT')),        // col 46
            'kip_number'               => $this->nullableStr($get('AU')),  // col 47
            'kip_name'                 => $this->nullableStr($get('AV')),  // col 48
            'kks_number'               => $this->nullableStr($get('AW')),  // col 49
            'pip_eligible'             => $this->yesNo($get('BB')),        // col 54
            'pip_reason'               => $this->nullableStr($get('BC')),  // col 55

            // Bank
            'bank_name'                => $this->nullableStr($get('AY')),  // col 51
            'bank_account_number'      => $this->nullableStr($get('AZ')),  // col 52
            'bank_account_name'        => $this->nullableStr($get('BA')),  // col 53

            // Fisik & Lokasi
            'special_needs'            => $this->nullableStr($get('BD')),           // col 56
            'latitude'                 => $this->nullableCoord($get('BG'), -90, 90),   // col 59
            'longitude'                => $this->nullableCoord($get('BH'), -180, 180), // col 60
            'weight'                   => $this->nullableInt($get('BJ')),  // col 62
            'height'                   => $this->nullableInt($get('BK')),  // col 63
            'head_circumference'       => $this->nullableInt($get('BL')),  // col 64
            'siblings_count'           => $this->nullableInt($get('BM')),  // col 65
            'home_distance'            => $this->nullableFloat($get('BN')), // col 66

            'is_active'                => true,
            'created_at'               => now(),
            'updated_at'               => now(),
        ];
    }

    private function buildClassMap(): array
    {
        return SchoolClass::all()->mapWithKeys(fn ($c) => [
            $c->name => $c->id,
            'Kelas ' . $c->name => $c->id,
        ])->toArray();
    }

    private function cellValue(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet, string $col, int $row): mixed
    {
        return $sheet->getCell("{$col}{$row}")->getValue();
    }

    private function nullableStr(mixed $v): ?string
    {
        $s = trim((string) $v);
        return $s === '' || $s === '0' ? null : $s;
    }

    private function nullableInt(mixed $v): ?int
    {
        if ($v === null || $v === '') return null;
        $i = (int) $v;
        return $i === 0 ? null : $i;
    }

    private function nullableFloat(mixed $v): ?float
    {
        if ($v === null || $v === '') return null;
        return (float) $v ?: null;
    }

    private function nullableCoord(mixed $v, float $min, float $max): ?float
    {
        if ($v === null || $v === '') return null;
        $f = (float) $v;
        if ($f === 0.0 || $f < $min || $f > $max) return null;
        return $f;
    }

    private function yesNo(mixed $v): ?bool
    {
        if ($v === null || $v === '') return null;
        return strtolower(trim((string) $v)) === 'ya';
    }

    private function mapGender(mixed $v): ?string
    {
        $g = strtoupper(trim((string) $v));
        return match ($g) {
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
        try {
            return (new \DateTime($s))->format('Y-m-d');
        } catch (\Throwable) {
            return null;
        }
    }

    private function uniqueSlug(string $name, string $nis): string
    {
        $base = Str::slug($name);
        $slug = $base;

        $existing = Student::where('slug', 'like', "{$base}%")->pluck('slug')->toArray();
        if (! in_array($slug, $existing)) {
            return $slug;
        }

        return $base . '-' . Str::slug($nis);
    }
}
