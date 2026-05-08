<?php

namespace App\Filament\Resources\Academics\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class AcademicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->label('Nama Program')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                TextInput::make('head_name')
                    ->label('Nama Kepala/Penanggung Jawab'),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                FileUpload::make('image')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('academics')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                RichEditor::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                RichEditor::make('curriculum')
                    ->label('Kurikulum')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
