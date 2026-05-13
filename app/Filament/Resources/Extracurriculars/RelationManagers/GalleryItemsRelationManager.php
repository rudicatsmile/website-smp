<?php

declare(strict_types=1);

namespace App\Filament\Resources\Extracurriculars\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'galleryItems';

    protected static ?string $title = 'Galeri Kegiatan';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            FileUpload::make('image')
                ->label('Foto')
                ->image()
                ->directory('ekskul/gallery')
                ->required()
                ->columnSpanFull(),

            TextInput::make('caption')->label('Keterangan')->nullable(),
            TextInput::make('order')->label('Urutan')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label('Foto')->square()->size(64),
                TextColumn::make('caption')->label('Keterangan')->limit(50),
                TextColumn::make('order')->label('Urutan')->sortable(),
            ])
            ->defaultSort('order')
            ->headerActions([CreateAction::make()->label('Upload Foto')])
            ->recordActions([EditAction::make(), DeleteAction::make()]);
    }
}
