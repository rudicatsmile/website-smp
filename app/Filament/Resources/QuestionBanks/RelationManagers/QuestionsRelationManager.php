<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuestionBanks\RelationManagers;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Soal';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')->label('Tipe Soal')->required()
                ->options([
                    'mcq' => 'Pilihan Ganda (1 jawaban)',
                    'multi' => 'Pilihan Ganda (banyak jawaban)',
                    'essay' => 'Essay',
                ])
                ->live()
                ->default('mcq'),
            Textarea::make('body')->label('Pertanyaan')->rows(4)->required()->columnSpanFull(),
            TextInput::make('score')->label('Skor')->numeric()->default(1)->minValue(1)->required(),
            TextInput::make('order')->label('Urutan')->numeric()->default(0),
            Textarea::make('explanation')->label('Pembahasan / Kunci')->rows(3)->columnSpanFull()
                ->helperText('Untuk soal essay, isi dengan rubrik/kunci jawaban (hanya guru yang melihat).'),
            Repeater::make('options')->label('Opsi Jawaban')
                ->relationship('options')
                ->visible(fn (callable $get) => in_array($get('type'), ['mcq', 'multi']))
                ->schema([
                    Textarea::make('label')->label('Teks Opsi')->rows(2)->required(),
                    Toggle::make('is_correct')->label('Jawaban benar')->inline(false),
                    TextInput::make('order')->label('Urutan')->numeric()->default(0),
                ])
                ->columns(3)
                ->defaultItems(4)
                ->minItems(2)
                ->reorderable()
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->columns([
                TextColumn::make('order')->label('No')->sortable(),
                TextColumn::make('type')->label('Tipe')->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'mcq' => 'PG',
                        'multi' => 'Multi',
                        'essay' => 'Essay',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        'mcq' => 'info',
                        'multi' => 'warning',
                        'essay' => 'gray',
                        default => 'gray',
                    }),
                TextColumn::make('body')->label('Pertanyaan')->html()->limit(80)->wrap(),
                TextColumn::make('score')->label('Skor')->badge(),
                TextColumn::make('options_count')->label('Opsi')->counts('options'),
            ])
            ->defaultSort('order')
            ->headerActions([CreateAction::make()])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }
}
