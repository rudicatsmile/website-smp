<?php

namespace App\Filament\Resources\Elections\ElectionResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class CandidatesRelationManager extends RelationManager
{
    protected static string $relationship = 'candidates';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('candidate_number')
                    ->required()
                    ->numeric()
                    ->label('Nomor Urut'),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Kandidat'),
                Forms\Components\FileUpload::make('photo')
                    ->image()
                    ->disk('public')
                    ->directory('candidates')
                    ->label('Foto Kandidat'),
                Forms\Components\TextInput::make('tagline')
                    ->maxLength(255)
                    ->label('Tagline/Slogan'),
                Forms\Components\RichEditor::make('vision')
                    ->columnSpanFull()
                    ->label('Visi'),
                Forms\Components\RichEditor::make('mission')
                    ->columnSpanFull()
                    ->label('Misi'),
                Forms\Components\RichEditor::make('experience')
                    ->columnSpanFull()
                    ->label('Pengalaman Organisasi'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('photo')
                    ->label('Foto')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('candidate_number')
                    ->label('Nomor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tagline')
                    ->label('Tagline')
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->actions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
