<?php

declare(strict_types=1);

namespace App\Filament\Resources\ExamSessions;

use App\Filament\Resources\ExamSessions\Pages\CreateExamSession;
use App\Filament\Resources\ExamSessions\Pages\EditExamSession;
use App\Filament\Resources\ExamSessions\Pages\ListExamSessions;
use App\Models\ExamSession;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ExamSessionResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = ExamSession::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'Ujian';

    protected static ?string $modelLabel = 'Ujian';

    protected static ?string $pluralModelLabel = 'Ujian';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Ujian')->columns(2)->schema([
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
                    ->label('Guru')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                Select::make('exam_type')
                    ->label('Jenis Ujian')
                    ->options(ExamSession::TYPES)
                    ->default('uts')
                    ->required(),
                TextInput::make('title')
                    ->label('Judul Ujian')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('UTS Matematika Kelas 7A Semester Ganjil 2025/2026'),
                DatePicker::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->required()
                    ->native(false),
                TextInput::make('academic_year')
                    ->label('Tahun Ajaran')
                    ->required()
                    ->default('2025/2026')
                    ->maxLength(20),
                Select::make('semester')
                    ->label('Semester')
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap'])
                    ->default('ganjil')
                    ->required(),
                TextInput::make('max_score')
                    ->label('Nilai Maksimal')
                    ->numeric()
                    ->default(100)
                    ->minValue(1)
                    ->maxValue(1000),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->rows(2)
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('exam_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('exam_type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ExamSession::TYPES[$state] ?? strtoupper($state))
                    ->color(fn ($record) => $record->type_color),
                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                TextColumn::make('schoolClass.name')
                    ->label('Kelas')
                    ->badge(),
                TextColumn::make('subject.name')
                    ->label('Mapel')
                    ->badge()
                    ->color('info'),
                TextColumn::make('academic_year')
                    ->label('T.A.')
                    ->toggleable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn ($s) => $s === 'ganjil' ? 'info' : 'warning'),
                TextColumn::make('scores_count')
                    ->label('Sudah Dinilai')
                    ->suffix(' siswa'),
                TextColumn::make('average_score')
                    ->label('Rata-rata')
                    ->numeric(1)
                    ->placeholder('—'),
            ])
            ->defaultSort('exam_date', 'desc')
            ->filters([
                SelectFilter::make('exam_type')
                    ->label('Jenis Ujian')
                    ->options(ExamSession::TYPES),
                SelectFilter::make('school_class_id')
                    ->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')
                    ->label('Mapel')
                    ->options(fn () => MaterialCategory::active()->ordered()->pluck('name', 'id')),
                SelectFilter::make('semester')
                    ->options(['ganjil' => 'Ganjil', 'genap' => 'Genap']),
            ])
            ->recordActions([
                Action::make('inputNilai')
                    ->label('Input Nilai')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.pages.input-nilai-ujian', ['exam' => $record->id])),
                EditAction::make()->label('Edit'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListExamSessions::route('/'),
            'create' => CreateExamSession::route('/create'),
            'edit'   => EditExamSession::route('/{record}/edit'),
        ];
    }
}
