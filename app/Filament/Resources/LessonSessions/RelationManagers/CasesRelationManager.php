<?php

declare(strict_types=1);

namespace App\Filament\Resources\LessonSessions\RelationManagers;

use App\Models\LessonSessionCase;
use App\Models\Student;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CasesRelationManager extends RelationManager
{
    protected static string $relationship = 'cases';

    protected static ?string $title = 'Kasus Peserta Didik';

    public function form(Schema $schema): Schema
    {
        $owner = $this->getOwnerRecord();

        return $schema->components([
            Section::make()->schema([
                Select::make('student_id')
                    ->label('Nama Siswa')
                    ->options(function () use ($owner) {
                        return Student::active()
                            ->where('school_class_id', $owner->school_class_id)
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                Textarea::make('problem')
                    ->label('Masalah / Kasus')
                    ->rows(3)
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Keterangan')
                    ->options(LessonSessionCase::STATUSES)
                    ->default('tidak_selesai')
                    ->required(),
                Textarea::make('follow_up')
                    ->label('Tindak Lanjut')
                    ->rows(3)
                    ->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('problem')
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('problem')
                    ->label('Masalah / Kasus')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->problem),
                TextColumn::make('status_label')
                    ->label('Keterangan')
                    ->badge()
                    ->color(fn ($record) => $record->status_color),
                TextColumn::make('follow_up')
                    ->label('Tindak Lanjut')
                    ->limit(50)
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Dicatat')
                    ->date('d M Y')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->headerActions([
                CreateAction::make()->label('Catat Kasus'),
            ])
            ->recordActions([
                EditAction::make()->label('Edit'),
                DeleteAction::make()->label('Hapus'),
            ])
            ->defaultSort('created_at', 'desc')
            ->emptyStateHeading('Belum Ada Kasus')
            ->emptyStateDescription('Klik "Catat Kasus" untuk menambah catatan kasus peserta didik.');
    }
}
