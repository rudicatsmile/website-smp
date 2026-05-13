<?php

declare(strict_types=1);

namespace App\Filament\Tahfidz\Resources;

use App\Filament\Tahfidz\Resources\ParticipantResource\Pages\CreateParticipant;
use App\Filament\Tahfidz\Resources\ParticipantResource\Pages\EditParticipant;
use App\Filament\Tahfidz\Resources\ParticipantResource\Pages\ListParticipants;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\TahfidzParticipant;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use BackedEnum;

class ParticipantResource extends Resource
{
    protected static ?string $model = TahfidzParticipant::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Enrollment Peserta';

    protected static ?string $modelLabel = 'Peserta';

    protected static ?string $pluralModelLabel = 'Peserta';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('school_class_id')
                ->label('Kelas')
                ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                ->placeholder('— Pilih Kelas (opsional) —')
                ->live()
                ->dehydrated(false),

            Select::make('student_id')
                ->label('Siswa')
                ->options(fn (Get $get) => Student::active()
                    ->when(
                        $get('school_class_id'),
                        fn ($q, $v) => $q->where('school_class_id', $v)
                    )
                    ->with('schoolClass')
                    ->get()
                    ->mapWithKeys(fn ($s) => [$s->id => $s->name . ' — ' . ($s->schoolClass?->name ?? '?')])
                )
                ->searchable()
                ->required(),

            TextInput::make('surah_target')
                ->label('Target Surah')
                ->numeric()
                ->minValue(1)
                ->maxValue(114)
                ->default(20)
                ->required(),

            DatePicker::make('enrolled_at')
                ->label('Tanggal Daftar')
                ->native(false)
                ->default(now()),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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
                    ->label('Target Surah')
                    ->alignCenter(),

                TextColumn::make('enrolled_at')
                    ->label('Tgl Daftar')
                    ->date('d M Y')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('school_class_id')
                    ->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->query(fn (Builder $query, array $data) =>
                        $data['value']
                            ? $query->whereHas('student', fn ($q) => $q->where('school_class_id', $data['value']))
                            : $query
                    ),

                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListParticipants::route('/'),
            'create' => CreateParticipant::route('/create'),
            'edit'   => EditParticipant::route('/{record}/edit'),
        ];
    }
}
