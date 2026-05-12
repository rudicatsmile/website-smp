<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use App\Models\ClassAssignment;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AssignmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'assignments';

    protected static ?string $title = 'Tugas';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('class_assignment_id')->label('Tugas')
                ->options(fn () => ClassAssignment::published()->orderBy('title')->pluck('title', 'id'))
                ->searchable()->preload()->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('assignment.title')
            ->columns([
                TextColumn::make('assignment.title')->label('Judul Tugas')->searchable()->limit(50),
                TextColumn::make('assignment.subject.name')->label('Mapel')->badge(),
                TextColumn::make('assignment.due_at')->label('Deadline')->dateTime('d M Y H:i'),
                TextColumn::make('given_at')->label('Diberikan')->dateTime('d M Y H:i')->placeholder('—'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
