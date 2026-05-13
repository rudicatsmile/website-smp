<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars;

use App\Filament\Resources\Extracurriculars\Pages\CreateExtracurricular;
use App\Filament\Resources\Extracurriculars\Pages\EditExtracurricular;
use App\Filament\Resources\Extracurriculars\Pages\ListExtracurriculars;
use App\Filament\Resources\Extracurriculars\RelationManagers\AchievementsRelationManager;
use App\Filament\Resources\Extracurriculars\RelationManagers\GalleryItemsRelationManager;
use App\Filament\Resources\Extracurriculars\RelationManagers\MembersRelationManager;
use App\Filament\Resources\Extracurriculars\RelationManagers\SchedulesRelationManager;
use App\Models\Extracurricular;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ExtracurricularResource extends Resource
{
    protected static ?string $model = Extracurricular::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static string|\UnitEnum|null $navigationGroup = 'Ekstrakurikuler';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Ekstrakurikuler';

    protected static ?string $modelLabel = 'Ekskul';

    protected static ?string $pluralModelLabel = 'Ekstrakurikuler';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('Nama Ekskul')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true),

            Select::make('category')
                ->label('Kategori')
                ->options([
                    'olahraga'   => 'Olahraga',
                    'seni'       => 'Seni & Budaya',
                    'keagamaan'  => 'Keagamaan',
                    'akademik'   => 'Akademik',
                    'lainnya'    => 'Lainnya',
                ])
                ->required()
                ->default('lainnya'),

            Select::make('coach_id')
                ->label('Pembina')
                ->options(fn () => StaffMember::active()->ordered()->pluck('name', 'id'))
                ->searchable()
                ->nullable(),

            TextInput::make('location')
                ->label('Lokasi Latihan')
                ->nullable(),

            TextInput::make('quota')
                ->label('Kuota Anggota')
                ->numeric()
                ->minValue(1)
                ->nullable(),

            TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),

            FileUpload::make('cover')
                ->label('Foto Cover')
                ->image()
                ->directory('ekskul/covers')
                ->nullable()
                ->columnSpanFull(),

            RichEditor::make('description')
                ->label('Deskripsi')
                ->nullable()
                ->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')
                    ->label('')
                    ->square()
                    ->size(48),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'olahraga'  => 'success',
                        'seni'      => 'warning',
                        'keagamaan' => 'info',
                        'akademik'  => 'primary',
                        default     => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'olahraga'  => 'Olahraga',
                        'seni'      => 'Seni & Budaya',
                        'keagamaan' => 'Keagamaan',
                        'akademik'  => 'Akademik',
                        default     => 'Lainnya',
                    }),

                TextColumn::make('coach.name')
                    ->label('Pembina')
                    ->sortable(),

                TextColumn::make('approved_count')
                    ->label('Anggota')
                    ->state(fn ($record) => $record->members()->where('status', 'approved')->count())
                    ->badge()
                    ->alignCenter(),

                TextColumn::make('pending_count')
                    ->label('Pending')
                    ->state(fn ($record) => $record->members()->where('status', 'pending')->count())
                    ->badge()
                    ->color('warning')
                    ->alignCenter(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'olahraga'  => 'Olahraga',
                        'seni'      => 'Seni & Budaya',
                        'keagamaan' => 'Keagamaan',
                        'akademik'  => 'Akademik',
                        'lainnya'   => 'Lainnya',
                    ]),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
            MembersRelationManager::class,
            AchievementsRelationManager::class,
            GalleryItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListExtracurriculars::route('/'),
            'create' => CreateExtracurricular::route('/create'),
            'edit'   => EditExtracurricular::route('/{record}/edit'),
        ];
    }
}
