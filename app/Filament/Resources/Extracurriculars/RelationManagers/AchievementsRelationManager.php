<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AchievementsRelationManager extends RelationManager
{
    protected static string $relationship = 'achievements';

    protected static ?string $title = 'Prestasi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Nama Prestasi')->required(),

            Select::make('level')
                ->label('Tingkat')
                ->options([
                    'sekolah'       => 'Sekolah',
                    'kecamatan'     => 'Kecamatan',
                    'kabupaten'     => 'Kabupaten/Kota',
                    'provinsi'      => 'Provinsi',
                    'nasional'      => 'Nasional',
                    'internasional' => 'Internasional',
                ])
                ->required()
                ->default('sekolah'),

            TextInput::make('rank')->label('Peringkat / Predikat')->nullable(),

            DatePicker::make('achieved_at')->label('Tanggal')->required()->native(false),

            Textarea::make('description')->label('Deskripsi')->nullable()->rows(2),

            FileUpload::make('cover')
                ->label('Foto Piala/Sertifikat')
                ->image()
                ->directory('ekskul/achievements')
                ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('cover')->label('')->square()->size(40),
                TextColumn::make('title')->label('Prestasi')->searchable(),
                TextColumn::make('level')->label('Tingkat')->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'kecamatan'     => 'Kecamatan',
                        'kabupaten'     => 'Kab/Kota',
                        'provinsi'      => 'Provinsi',
                        'nasional'      => 'Nasional',
                        'internasional' => 'Internasional',
                        default         => 'Sekolah',
                    }),
                TextColumn::make('rank')->label('Peringkat'),
                TextColumn::make('achieved_at')->label('Tanggal')->date('d M Y')->sortable(),
            ])
            ->defaultSort('achieved_at', 'desc')
            ->headerActions([CreateAction::make()->label('Tambah Prestasi')])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
