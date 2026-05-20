<?php

declare(strict_types=1);

namespace App\Filament\Resources\Grades\Pages;

use App\Filament\Resources\Grades\GradeResource;
use App\Models\Grade;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\Student;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListGrades extends ListRecords
{
    protected static string $resource = GradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('buatLedger')
                ->label('Buat Ledger Nilai')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    Select::make('school_class_id')
                        ->label('Kelas')
                        ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('material_category_id')
                        ->label('Mata Pelajaran')
                        ->options(fn () => MaterialCategory::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('staff_member_id')
                        ->label('Guru Pengampu')
                        ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                        ->searchable()
                        ->preload(),
                    TextInput::make('academic_year')
                        ->label('Tahun Ajaran')
                        ->default('2025/2026')
                        ->required()
                        ->maxLength(20),
                    Select::make('semester')
                        ->label('Semester')
                        ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap'])
                        ->default('ganjil')
                        ->required(),
                ])
                ->modalHeading('Buat Ledger Nilai Kelas')
                ->modalDescription('Sistem akan membuat record nilai kosong untuk semua siswa aktif di kelas yang dipilih. Record yang sudah ada tidak akan ditimpa.')
                ->modalSubmitActionLabel('Buat Sekarang')
                ->action(function (array $data): void {
                    $subject = MaterialCategory::find($data['material_category_id'])?->name ?? '—';

                    $students = Student::active()
                        ->where('school_class_id', $data['school_class_id'])
                        ->get();

                    $created = 0;
                    foreach ($students as $student) {
                        $exists = Grade::where('student_id', $student->id)
                            ->where('subject', $subject)
                            ->where('academic_year', $data['academic_year'])
                            ->where('semester', $data['semester'])
                            ->exists();

                        if (! $exists) {
                            Grade::create([
                                'student_id'     => $student->id,
                                'staff_member_id' => $data['staff_member_id'] ?? null,
                                'subject'        => $subject,
                                'academic_year'  => $data['academic_year'],
                                'semester'       => $data['semester'],
                            ]);
                            $created++;
                        }
                    }

                    $skipped = $students->count() - $created;

                    Notification::make()
                        ->title("{$created} record nilai dibuat" . ($skipped > 0 ? ", {$skipped} sudah ada (dilewati)" : ''))
                        ->success()
                        ->send();
                }),

            CreateAction::make()->label('Tambah Manual'),
        ];
    }
}
