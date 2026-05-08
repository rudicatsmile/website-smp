<?php

namespace App\Filament\Resources\Galleries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('title')
                    ->label('Judul Album')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                    ->columnSpanFull(),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->columnSpanFull(),
                FileUpload::make('cover')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('galleries/covers')
                    ->visibility('public')
                    ->maxSize(2048)
                    ->columnSpanFull(),
                Textarea::make('description')
                    ->rows(3)
                    ->columnSpanFull(),
                DateTimePicker::make('published_at')
                    ->default(now()),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
