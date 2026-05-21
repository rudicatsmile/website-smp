<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use App\Models\AssessmentType;
use App\Models\SessionAssessment;
use App\Models\StudentAttendance;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssessmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assessments';

    protected static ?string $title = 'Assessment';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()->columns(2)->schema([
                TextInput::make('title')
                    ->label('Judul Penilaian')
                    ->required()
                    ->maxLength(200)
                    ->placeholder('Kuis Bab 3, Ulangan Harian 1, ...')
                    ->columnSpanFull(),
                Select::make('type')
                    ->label('Jenis')
                    ->options(fn () => AssessmentType::active()->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('max_score')
                    ->label('Nilai Maksimal')
                    ->numeric()
                    ->default(100)
                    ->minValue(1)
                    ->maxValue(1000)
                    ->required(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public function table(Table $table): Table
    {
        $owner = $this->getOwnerRecord();

        return $table
            ->recordTitleAttribute('title')
            ->heading(function () use ($owner) {
                $date       = $owner->session_date?->toDateString();
                $classId    = $owner->school_class_id;
                $hasAbsensi = $date && $classId && StudentAttendance::query()
                    ->where('date', $date)
                    ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
                    ->exists();

                return $hasAbsensi ? null : '⚠️ Belum ada absensi di tanggal sesi ini — input absensi dulu sebelum menilai!';
            })
            ->columns([
                TextColumn::make('title')
                    ->label('Judul Penilaian')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('type_label')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn ($record) => $record->type_color),
                TextColumn::make('domain_label')
                    ->label('Domain')
                    ->badge()
                    ->color(fn ($record) => $record->domain_color)
                    ->placeholder('—'),
                TextColumn::make('max_score')
                    ->label('Maks')
                    ->numeric(0),
                TextColumn::make('scores_count')
                    ->label('Sudah Dinilai')
                    ->suffix(' siswa'),
                TextColumn::make('average_score')
                    ->label('Rata-rata')
                    ->numeric(1)
                    ->placeholder('—'),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Penilaian')
                    ->mutateFormDataUsing(function (array $data) use ($owner) {
                        $date    = $owner->session_date?->toDateString();
                        $classId = $owner->school_class_id;

                        if ($date && $classId) {
                            $hasAbsensi = StudentAttendance::query()
                                ->where('date', $date)
                                ->whereHas('student', fn ($q) => $q->where('school_class_id', $classId))
                                ->exists();

                            if (! $hasAbsensi) {
                                Notification::make()
                                    ->title('Peringatan: Belum Ada Absensi')
                                    ->body('Absensi pada tanggal sesi belum diisi. Nilai akan diterapkan ke semua siswa aktif.')
                                    ->warning()
                                    ->persistent()
                                    ->send();
                            }
                        }

                        return $data;
                    }),
            ])
            ->recordActions([
                Action::make('inputNilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.pages.input-nilai-sesi', ['assessment' => $record->id])),
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
