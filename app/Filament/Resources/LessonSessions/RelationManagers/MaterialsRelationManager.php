<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $title = 'Materi';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('Judul Materi')->searchable()->limit(50),
                TextColumn::make('subject.name')->label('Mapel')->badge(),
                TextColumn::make('teacher.name')->label('Guru')->toggleable(),
            ])
            ->headerActions([
                AttachAction::make()->label('Tambah Materi')->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make()->label('Lepas'),
            ]);
    }
}
