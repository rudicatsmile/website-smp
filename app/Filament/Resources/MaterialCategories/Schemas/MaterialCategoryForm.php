<?php

declare(strict_types=1);

namespace App\Filament\Resources\MaterialCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MaterialCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori Materi')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Mata Pelajaran')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('icon')
                            ->label('Heroicon (mis. book-open)')
                            ->maxLength(50),
                        TextInput::make('color')
                            ->label('Tailwind Color (mis. emerald)')
                            ->default('emerald')
                            ->maxLength(50),
                        TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }
}
