<?php

namespace App\Filament\Resources\News\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class NewsForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konten')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state)))
                            ->columnSpanFull(),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('author_id')
                            ->label('Penulis')
                            ->relationship('author', 'name')
                            ->default(fn () => auth()->id())
                            ->searchable()
                            ->preload(),
                        Select::make('tags')
                            ->multiple()
                            ->relationship('tags', 'name')
                            ->preload()
                            ->columnSpanFull(),
                        Textarea::make('excerpt')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->columnSpanFull(),
                        RichEditor::make('body')
                            ->label('Isi Berita')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('news')
                            ->maxSize(2048)
                            ->columnSpanFull(),
                    ]),
                Section::make('Publikasi')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'])
                            ->default('draft')
                            ->required(),
                        DateTimePicker::make('published_at')
                            ->default(now()),
                    ]),
                Section::make('SEO')
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')->maxLength(160),
                        Textarea::make('meta_description')->rows(2)->maxLength(500),
                    ]),
            ]);
    }
}
