<?php

declare(strict_types=1);

namespace App\Filament\Resources\StudentViolations;

use App\Filament\Resources\StudentViolations\Pages\CreateStudentViolation;
use App\Filament\Resources\StudentViolations\Pages\EditStudentViolation;
use App\Filament\Resources\StudentViolations\Pages\ListStudentViolations;
use App\Models\StaffMember;
use App\Models\Student;
use App\Models\StudentViolation;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class StudentViolationResource extends Resource
{
    protected static ?string $model = StudentViolation::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Pelanggaran';

    protected static ?string $modelLabel = 'Pelanggaran';

    protected static ?string $pluralModelLabel = 'Pelanggaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 30;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Data Pelanggaran')->columns(2)->schema([
                Select::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                DatePicker::make('date')->label('Tanggal')->required()->default(today()),
                Select::make('category')->label('Kategori')
                    ->options(StudentViolation::CATEGORIES)->required()->default('kedisiplinan'),
                TextInput::make('points')->label('Poin')->numeric()->default(5)->minValue(0)->maxValue(100)->required(),
                TextInput::make('description')->label('Pelanggaran')->required()->maxLength(255)->columnSpanFull(),
                TextInput::make('action_taken')->label('Tindakan')->maxLength(255)->columnSpanFull(),
                Select::make('staff_member_id')->label('Pencatat')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')->label('Tanggal')->date('d M Y')->sortable(),
                TextColumn::make('student.name')->label('Siswa')->searchable()->sortable(),
                TextColumn::make('category_label')->label('Kategori')->badge(),
                TextColumn::make('description')->label('Pelanggaran')->limit(40)->searchable(),
                TextColumn::make('points')->label('Poin')->badge()
                    ->color(fn ($s) => $s >= 20 ? 'danger' : ($s >= 10 ? 'warning' : 'info')),
                TextColumn::make('action_taken')->label('Tindakan')->limit(30)->toggleable(),
                TextColumn::make('recorder.name')->label('Pencatat')->toggleable()->placeholder('—'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                SelectFilter::make('category')->options(StudentViolation::CATEGORIES),
                SelectFilter::make('student_id')->label('Siswa')
                    ->options(fn () => Student::active()->orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher', 'counselor']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudentViolations::route('/'),
            'create' => CreateStudentViolation::route('/create'),
            'edit' => EditStudentViolation::route('/{record}/edit'),
        ];
    }
}
