<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use App\Models\ClassMaterial;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $title = 'Materi';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('class_material_id')->label('Materi')
                ->options(fn () => ClassMaterial::published()->orderBy('title')->pluck('title', 'id'))
                ->searchable()->preload()->required(),
            TextInput::make('order')->label('Urutan')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('material.title')
            ->columns([
                TextColumn::make('order')->label('#')->sortable(),
                TextColumn::make('material.title')->label('Judul Materi')->searchable()->limit(50),
                TextColumn::make('material.subject.name')->label('Mapel')->badge(),
            ])
            ->defaultSort('order');
    }
}
