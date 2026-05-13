<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $title = 'Tugas';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('title')->label('Judul Tugas')->searchable()->limit(50),
                TextColumn::make('subject.name')->label('Mapel')->badge(),
                TextColumn::make('due_at')->label('Deadline')->dateTime('d M Y H:i')->placeholder('—'),
            ])
            ->headerActions([
                AttachAction::make()->label('Tambah Tugas')->preloadRecordSelect(),
            ])
            ->recordActions([
                DetachAction::make()->label('Lepas'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
