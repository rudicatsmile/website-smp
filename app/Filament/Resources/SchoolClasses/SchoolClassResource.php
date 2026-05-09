<?php

declare(strict_types=1);

namespace App\Filament\Resources\SchoolClasses;

use App\Filament\Resources\SchoolClasses\Pages\CreateSchoolClass;
use App\Filament\Resources\SchoolClasses\Pages\EditSchoolClass;
use App\Filament\Resources\SchoolClasses\Pages\ListSchoolClasses;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

class SchoolClassResource extends Resource
{
    protected static ?string $model = SchoolClass::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Kelas';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Kelas')->columns(2)->schema([
                TextInput::make('grade')->label('Tingkat')->numeric()->required()->minValue(7)->maxValue(9),
                TextInput::make('section')->label('Rombel')->required()->maxLength(8),
                TextInput::make('name')->label('Nama Kelas')->required()->maxLength(32)->helperText('Contoh: 7A, 8B'),
                TextInput::make('academic_year')->label('Tahun Ajaran')->required()->default('2025/2026')->maxLength(16),
                Select::make('homeroom_teacher_id')
                    ->label('Wali Kelas')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),
                TextInput::make('order')->label('Urutan')->numeric()->default(0),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Kelas')->sortable()->searchable(),
                TextColumn::make('grade')->label('Tingkat')->sortable(),
                TextColumn::make('section')->label('Rombel'),
                TextColumn::make('academic_year')->label('Tahun Ajaran'),
                TextColumn::make('homeroomTeacher.name')->label('Wali Kelas')->placeholder('—'),
                TextColumn::make('students_count')->counts('students')->label('Siswa')->badge()->color('primary'),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->defaultSort('grade')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchoolClasses::route('/'),
            'create' => CreateSchoolClass::route('/create'),
            'edit' => EditSchoolClass::route('/{record}/edit'),
        ];
    }
}
