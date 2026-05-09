<?php

declare(strict_types=1);

namespace App\Filament\Resources\ClassAssignments\RelationManagers;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;

class SubmissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'submissions';

    protected static ?string $title = 'Submission Siswa';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('score')->label('Skor')->numeric()->minValue(0)->maxValue(100),
            Textarea::make('feedback')->label('Feedback')->rows(3),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('student.name')->label('Siswa')->searchable(),
                TextColumn::make('student.schoolClass.name')->label('Kelas')->badge(),
                TextColumn::make('submitted_at')->label('Submit')->dateTime('d M Y H:i')
                    ->placeholder('Belum submit'),
                TextColumn::make('score')->label('Skor')->badge()->color(fn ($state) => $state !== null ? 'success' : 'gray')
                    ->placeholder('Belum dinilai'),
                TextColumn::make('status')->label('Status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'graded' => 'success',
                        'submitted' => 'info',
                        'late' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                Action::make('grade')
                    ->label('Nilai')
                    ->icon('heroicon-o-academic-cap')
                    ->color('primary')
                    ->schema([
                        TextInput::make('score')->label('Skor')->numeric()->minValue(0)->maxValue(100)->required(),
                        Textarea::make('feedback')->label('Feedback')->rows(3),
                    ])
                    ->fillForm(fn ($record) => ['score' => $record->score, 'feedback' => $record->feedback])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'score' => $data['score'],
                            'feedback' => $data['feedback'] ?? null,
                            'graded_at' => now(),
                            'graded_by' => auth()->user()?->staffMember?->id,
                        ]);
                        Notification::make()->title('Nilai tersimpan')->success()->send();
                    }),
                EditAction::make(),
            ]);
    }
}
