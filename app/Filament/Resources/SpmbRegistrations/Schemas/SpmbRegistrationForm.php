<?php

namespace App\Filament\Resources\SpmbRegistrations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SpmbRegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Verifikasi Panitia')
                    ->columns(2)
                    ->schema([
                        Select::make('status')
                            ->options([
                                'pending' => 'Menunggu',
                                'verifying' => 'Sedang Diverifikasi',
                                'accepted' => 'Diterima',
                                'rejected' => 'Tidak Diterima',
                                'waiting_list' => 'Daftar Tunggu',
                            ])
                            ->default('pending')
                            ->required(),
                        TextInput::make('registration_number')
                            ->disabled()
                            ->dehydrated(false),
                        Textarea::make('admin_note')
                            ->label('Catatan untuk Pendaftar')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),
                Section::make('Periode')
                    ->schema([
                        Select::make('spmb_period_id')
                            ->label('Periode')
                            ->relationship('period', 'name')
                            ->required()
                            ->disabled()
                            ->dehydrated(),
                    ]),
                Section::make('Data Calon Siswa')
                    ->columns(2)
                    ->schema([
                        TextInput::make('full_name')->required()->columnSpanFull(),
                        TextInput::make('nick_name'),
                        Select::make('gender')->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])->required(),
                        TextInput::make('birth_place')->required(),
                        DatePicker::make('birth_date')->required(),
                        TextInput::make('nik')->label('NIK'),
                        TextInput::make('nisn')->label('NISN'),
                        TextInput::make('religion')->label('Agama'),
                        TextInput::make('phone')->tel(),
                        TextInput::make('email')->email(),
                        Textarea::make('address')->required()->rows(3)->columnSpanFull(),
                    ]),
                Section::make('Data Orang Tua & Sekolah Asal')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('father_name')->label('Nama Ayah'),
                        TextInput::make('father_job')->label('Pekerjaan Ayah'),
                        TextInput::make('father_phone')->label('No. HP Ayah')->tel(),
                        TextInput::make('mother_name')->label('Nama Ibu'),
                        TextInput::make('mother_job')->label('Pekerjaan Ibu'),
                        TextInput::make('mother_phone')->label('No. HP Ibu')->tel(),
                        TextInput::make('guardian_name')->label('Nama Wali')->columnSpanFull(),
                        TextInput::make('previous_school')->label('Asal Sekolah'),
                        TextInput::make('graduation_year')->label('Tahun Lulus'),
                        TextInput::make('npsn')->label('NPSN'),
                    ]),
            ]);
    }
}
