<?php

declare(strict_types=1);

namespace App\Filament\Resources\Alumni;

use App\Filament\Resources\Alumni\Pages\CreateAlumni;
use App\Filament\Resources\Alumni\Pages\EditAlumni;
use App\Filament\Resources\Alumni\Pages\ListAlumni;
use App\Models\Alumni;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AlumniResource extends Resource
{
    protected static ?string $model = Alumni::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::AcademicCap;

    protected static string|\UnitEnum|null $navigationGroup = 'Alumni';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Profil Alumni';

    protected static ?string $modelLabel = 'Alumni';

    protected static ?string $pluralModelLabel = 'Profil Alumni';

    protected static ?string $slug = 'alumni/profil';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('photo')
                ->label('Foto')
                ->image()
                ->directory('alumni/photos')
                ->nullable()
                ->columnSpanFull(),

            TextInput::make('name')
                ->label('Nama Lengkap')
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, callable $set) =>
                    $set('slug', Str::slug($state))
                ),

            TextInput::make('slug')
                ->label('Slug')
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('graduation_year')
                ->label('Tahun Lulus')
                ->numeric()
                ->required()
                ->minValue(1990)
                ->maxValue((int) date('Y')),

            Select::make('current_status')
                ->label('Status Saat Ini')
                ->options([
                    'working'      => 'Bekerja',
                    'studying'     => 'Kuliah',
                    'entrepreneur' => 'Wirausaha',
                    'both'         => 'Kuliah & Bekerja',
                    'other'        => 'Lainnya',
                ])
                ->required()
                ->default('working'),

            TextInput::make('company_or_institution')
                ->label('Perusahaan / Institusi')
                ->nullable(),

            TextInput::make('position')
                ->label('Jabatan / Jurusan')
                ->nullable(),

            TextInput::make('city')
                ->label('Kota')
                ->nullable(),

            TextInput::make('linkedin_url')
                ->label('LinkedIn URL')
                ->url()
                ->nullable()
                ->columnSpanFull(),

            Textarea::make('quote')
                ->label('Kutipan Inspiratif')
                ->rows(2)
                ->maxLength(500)
                ->nullable()
                ->columnSpanFull(),

            RichEditor::make('story')
                ->label('Perjalanan & Cerita Sukses')
                ->nullable()
                ->columnSpanFull(),

            TextInput::make('order')
                ->label('Urutan')
                ->numeric()
                ->default(0),

            Toggle::make('is_featured')
                ->label('Featured')
                ->default(true),

            Toggle::make('is_published')
                ->label('Dipublikasikan')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')
                    ->label('')
                    ->circular()
                    ->size(44),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable()
                    ->weight('semibold'),

                TextColumn::make('graduation_year')
                    ->label('Thn Lulus')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('current_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'working'      => 'success',
                        'studying'     => 'info',
                        'entrepreneur' => 'warning',
                        'both'         => 'primary',
                        default        => 'gray',
                    })
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'working'      => 'Bekerja',
                        'studying'     => 'Kuliah',
                        'entrepreneur' => 'Wirausaha',
                        'both'         => 'Kuliah & Kerja',
                        default        => 'Lainnya',
                    }),

                TextColumn::make('company_or_institution')
                    ->label('Perusahaan/Inst.')
                    ->limit(30)
                    ->placeholder('—'),

                TextColumn::make('position')
                    ->label('Jabatan')
                    ->limit(25)
                    ->placeholder('—'),

                IconColumn::make('is_featured')
                    ->label('Featured')
                    ->boolean(),

                IconColumn::make('is_published')
                    ->label('Publik')
                    ->boolean(),
            ])
            ->defaultSort('order')
            ->filters([
                SelectFilter::make('current_status')
                    ->label('Status')
                    ->options([
                        'working'      => 'Bekerja',
                        'studying'     => 'Kuliah',
                        'entrepreneur' => 'Wirausaha',
                        'both'         => 'Kuliah & Bekerja',
                        'other'        => 'Lainnya',
                    ]),

                TernaryFilter::make('is_published')->label('Dipublikasikan'),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListAlumni::route('/'),
            'create' => CreateAlumni::route('/create'),
            'edit'   => EditAlumni::route('/{record}/edit'),
        ];
    }
}
