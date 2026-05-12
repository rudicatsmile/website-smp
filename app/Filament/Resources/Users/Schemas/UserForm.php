<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(120),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(160),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->helperText('Kosongkan untuk tidak mengubah password (saat edit).'),
                Select::make('roles')
                    ->label('Role')
                    ->multiple()
                    ->options(fn () => Role::pluck('name', 'name'))
                    ->afterStateHydrated(function ($component, $record) {
                        if ($record) {
                            $component->state($record->roles->pluck('name')->toArray());
                        }
                    })
                    ->saveRelationshipsUsing(function ($state, $record) {
                        $record->syncRoles($state ?? []);
                    }),
                Toggle::make('is_active')
                    ->default(true)
                    ->required(),
            ]);
    }
}
