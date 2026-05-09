<?php

namespace App\Filament\Widgets;

use App\Models\News;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentNews extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $heading = 'Berita Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(News::latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable(),
            ])
            ->paginated(false);
    }
}
