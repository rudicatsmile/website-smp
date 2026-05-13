<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Resources;

use App\Filament\Tahfidz\Resources\RekapNilaiResource\Pages\ListRekapNilai;
use App\Models\TahfidzParticipant;
use App\Models\SchoolClass;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class RekapNilaiResource extends Resource
{
    protected static ?string $model = TahfidzParticipant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'Rekap Nilai';

    protected static ?string $modelLabel = 'Rekap';

    protected static ?string $pluralModelLabel = 'Rekap Nilai';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                TahfidzParticipant::query()
                    ->with(['student.schoolClass', 'grades'])
                    ->active()
            )
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->badge()
                    ->sortable(),

                TextColumn::make('student.nis')
                    ->label('NIS')
                    ->searchable(),

                TextColumn::make('surah_target')
                    ->label('Target')
                    ->alignCenter(),

                TextColumn::make('surah_selesai')
                    ->label('Surah Selesai')
                    ->getStateUsing(fn (TahfidzParticipant $record) => $record->grades->count() . ' / ' . $record->surah_target)
                    ->alignCenter()
                    ->badge()
                    ->color('emerald'),

                TextColumn::make('progres_persen')
                    ->label('Progres (%)')
                    ->getStateUsing(fn (TahfidzParticipant $record) => $record->progres_present . '%')
                    ->alignCenter()
                    ->badge()
                    ->color(fn (TahfidzParticipant $record) => match(true) {
                        $record->progres_present >= 80 => 'success',
                        $record->progres_present >= 50 => 'warning',
                        default                         => 'danger',
                    }),

                TextColumn::make('nilai_rata_rata')
                    ->label('Nilai Rata-Rata')
                    ->getStateUsing(fn (TahfidzParticipant $record) => $record->nilai_rata_rata)
                    ->alignCenter()
                    ->badge()
                    ->color('indigo'),
            ])
            ->filters([
                SelectFilter::make('school_class')
                    ->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('student', fn ($q) => $q->where('school_class_id', $data['value']))
                            : $query
                    ),
            ])
            ->defaultSort('student.name');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRekapNilai::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
