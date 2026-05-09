<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassMaterials;

use App\Filament\Resources\ClassMaterials\Pages\CreateClassMaterial;
use App\Filament\Resources\ClassMaterials\Pages\EditClassMaterial;
use App\Filament\Resources\ClassMaterials\Pages\ListClassMaterials;
use App\Models\ClassMaterial;
use App\Models\MaterialCategory;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Support\Str;

class ClassMaterialResource extends Resource
{
    protected static ?string $model = ClassMaterial::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Materi Kelas';

    protected static ?string $modelLabel = 'Materi Kelas';

    protected static ?string $pluralModelLabel = 'Materi Kelas';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Materi')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state . '-' . now()->format('YmdHis'))))
                    ->columnSpanFull(),
                TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(255)->columnSpanFull(),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->placeholder('— Semua kelas —'),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('staff_member_id')->label('Guru')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->default(fn () => auth()->user()?->staffMember?->id),
                Toggle::make('is_published')->label('Terbit')->default(true),
                Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                FileUpload::make('file_path')->label('Berkas')
                    ->disk('public')->directory('class-materials')
                    ->openable()->downloadable()->maxSize(20480)
                    ->columnSpanFull()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state && is_object($state) && method_exists($state, 'getSize')) {
                            $set('file_size', $state->getSize());
                        }
                    }),
                TextInput::make('file_size')->hidden(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge()->placeholder('Semua'),
                TextColumn::make('subject.name')->label('Mapel')->toggleable(),
                TextColumn::make('teacher.name')->label('Guru')->toggleable(),
                IconColumn::make('is_published')->label('Terbit')->boolean(),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y')->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'editor', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassMaterials::route('/'),
            'create' => CreateClassMaterial::route('/create'),
            'edit' => EditClassMaterial::route('/{record}/edit'),
        ];
    }
}
