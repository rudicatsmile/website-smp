<?php

declare(strict_types=1);

namespace App\Filament\Resources\SpmbRegistrations\RelationManagers;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Dokumen';

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('type')
                ->options([
                    'kk' => 'Kartu Keluarga',
                    'akta' => 'Akta Kelahiran',
                    'foto' => 'Pas Foto',
                    'ijazah' => 'Ijazah / SKL',
                    'raport' => 'Rapor',
                    'lain' => 'Lainnya',
                ])
                ->required(),
            FileUpload::make('file_path')
                ->label('File')
                ->required()
                ->disk('public')
                ->directory('spmb')
                ->visibility('public')
                ->maxSize(5120)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')->badge(),
                TextColumn::make('file_path')->label('File')->limit(40),
                IconColumn::make('verified')->boolean()->label('Verified'),
                TextColumn::make('created_at')->dateTime('d M Y H:i')->label('Diunggah'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Lihat')
                    ->icon('heroicon-m-eye')
                    ->url(fn ($record) => Storage::disk('public')->url($record->file_path), shouldOpenInNewTab: true),
                Action::make('toggle_verify')
                    ->label(fn ($record) => $record->verified ? 'Batal Verifikasi' : 'Verifikasi')
                    ->icon('heroicon-m-check-badge')
                    ->action(fn ($record) => $record->update(['verified' => ! $record->verified])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
