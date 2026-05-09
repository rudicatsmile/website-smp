<?php

namespace App\Filament\Widgets;

use App\Models\ContactMessage;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessages extends BaseWidget
{
    protected static ?int $sort = 2;
    protected static ?string $heading = 'Pesan Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(ContactMessage::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(30)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Diterima')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
