<?php

declare(strict_types=1);

namespace App\Filament\Resources\QuestionBanks;

use App\Filament\Resources\QuestionBanks\Pages\CreateQuestionBank;
use App\Filament\Resources\QuestionBanks\Pages\EditQuestionBank;
use App\Filament\Resources\QuestionBanks\Pages\ListQuestionBanks;
use App\Filament\Resources\QuestionBanks\RelationManagers\QuestionsRelationManager;
use App\Models\MaterialCategory;
use App\Models\QuestionBank;
use App\Models\StaffMember;
use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class QuestionBankResource extends Resource
{
    protected static ?string $model = QuestionBank::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Bank Soal';

    protected static ?string $modelLabel = 'Bank Soal';

    protected static ?string $pluralModelLabel = 'Bank Soal';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 14;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Bank Soal')->columns(2)->schema([
                TextInput::make('title')->label('Judul')->required()->maxLength(255)->columnSpanFull(),
                Select::make('material_category_id')->label('Mata Pelajaran')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload(),
                Select::make('staff_member_id')->label('Penyusun')
                    ->options(fn () => StaffMember::active()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->preload()
                    ->default(fn () => auth()->user()?->staffMember?->id),
                Textarea::make('description')->label('Deskripsi')->rows(3)->columnSpanFull(),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable()->limit(40),
                TextColumn::make('subject.name')->label('Mapel')->badge()->toggleable(),
                TextColumn::make('teacher.name')->label('Penyusun')->toggleable(),
                TextColumn::make('questions_count')->label('Jml Soal')->counts('questions')->badge(),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
                TextColumn::make('updated_at')->label('Diperbarui')->since()->toggleable(),
            ])
            ->defaultSort('updated_at', 'desc')
            ->filters([
                SelectFilter::make('material_category_id')->label('Mapel')
                    ->options(fn () => MaterialCategory::orderBy('name')->pluck('name', 'id')),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getRelations(): array
    {
        return [QuestionsRelationManager::class];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if ($user && $user->hasRole('teacher') && ! $user->hasAnyRole(['super_admin', 'admin'])) {
            $query->where('staff_member_id', $user->staffMember?->id);
        }
        return $query;
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'teacher']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuestionBanks::route('/'),
            'create' => CreateQuestionBank::route('/create'),
            'edit' => EditQuestionBank::route('/{record}/edit'),
        ];
    }
}
