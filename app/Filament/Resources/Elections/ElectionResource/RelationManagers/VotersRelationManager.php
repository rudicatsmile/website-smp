<?php

namespace App\Filament\Resources\Elections\ElectionResource\RelationManagers;

use App\Models\Student;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class VotersRelationManager extends RelationManager
{
    protected static string $relationship = 'voters';

    protected static ?string $recordTitleAttribute = 'token';

    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->label('Siswa')
                    ->options(Student::query()->active()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('token')
                    ->label('Token')
                    ->required()
                    ->maxLength(10)
                    ->default(fn () => strtoupper(Str::random(6)))
                    ->disabled()
                    ->dehydrated(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('token')
            ->columns([
                Tables\Columns\TextColumn::make('student.nisn')
                    ->label('NISN')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('student.schoolClass.name')
                    ->label('Kelas')
                    ->sortable(),
                Tables\Columns\TextColumn::make('token')
                    ->label('Token')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('has_voted')
                    ->label('Sudah Memilih')
                    ->boolean(),
                Tables\Columns\TextColumn::make('voted_at')
                    ->label('Waktu Memilih')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('has_voted')
                    ->label('Status Memilih')
                    ->placeholder('Semua')
                    ->trueLabel('Sudah Memilih')
                    ->falseLabel('Belum Memilih'),
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['token'] = strtoupper(Str::random(6));
                        return $data;
                    }),
                \Filament\Actions\Action::make('generate_tokens')
                    ->label('Generate Tokens Massal')
                    ->icon('heroicon-o-key')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Token untuk Semua Siswa Aktif')
                    ->modalDescription('Apakah Anda yakin ingin men-generate token bagi seluruh siswa aktif yang belum terdaftar sebagai pemilih pada pemilu ini?')
                    ->action(function (RelationManager $livewire) {
                        $election = $livewire->getOwnerRecord();
                        $students = Student::query()
                            ->active()
                            ->whereDoesntHave('elections', function ($query) use ($election) {
                                $query->where('election_id', $election->id);
                            }) // Using Eloquent subquery via a relation we will add
                            ->get();
                        
                        // Fallback check if relation not working perfectly
                        $existingStudentIds = $election->voters()->pluck('student_id')->toArray();
                        $students = Student::active()->whereNotIn('id', $existingStudentIds)->get();

                        $count = 0;
                        foreach ($students as $student) {
                            $election->voters()->create([
                                'student_id' => $student->id,
                                'token' => strtoupper(Str::random(6)),
                            ]);
                            $count++;
                        }

                        Notification::make()
                            ->title("Berhasil men-generate {$count} token baru.")
                            ->success()
                            ->send();
                    }),
            ])
            ->actions([
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
