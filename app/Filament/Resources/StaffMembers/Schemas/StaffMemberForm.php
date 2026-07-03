<?php

declare(strict_types=1);

namespace App\Filament\Resources\StaffMembers\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class StaffMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        TextInput::make('nip')
                            ->label('NIP')
                            ->maxLength(50),
                        TextInput::make('nuptk')
                            ->label('NUPTK')
                            ->maxLength(50),
                        Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ]),
                        TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->maxLength(100),
                        DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->native(false),
                    ]),

                Section::make('Jabatan')
                    ->columns(2)
                    ->schema([
                        Select::make('staff_category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('position')
                            ->label('Jabatan')
                            ->maxLength(255),
                        Toggle::make('is_principal')
                            ->label('Kepala Sekolah')
                            ->default(false),
                    ]),

                Section::make('Akademik')
                    ->schema([
                        Select::make('teachingSubjects')
                            ->label('Mata Pelajaran')
                            ->relationship('teachingSubjects', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->columnSpanFull(),
                        Repeater::make('education')
                            ->label('Pendidikan')
                            ->schema([
                                TextInput::make('degree')
                                    ->label('Gelar')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('major')
                                    ->label('Jurusan')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('institution')
                                    ->label('Institusi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Tahun Lulus')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['degree'] . ' - ' . ($state['institution'] ?? '') ?? null)
                            ->default([]),
                        Repeater::make('certifications')
                            ->label('Sertifikasi')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Sertifikasi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('issuer')
                                    ->label('Penerbit')
                                    ->maxLength(255),
                                TextInput::make('year')
                                    ->label('Tahun')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['name'] ?? null)
                            ->default([]),
                        Repeater::make('experiences')
                            ->label('Pengalaman Kerja')
                            ->schema([
                                TextInput::make('position')
                                    ->label('Posisi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('organization')
                                    ->label('Organisasi')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('start_year')
                                    ->label('Tahun Mulai')
                                    ->maxLength(10),
                                TextInput::make('end_year')
                                    ->label('Tahun Selesai')
                                    ->maxLength(10),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['position'] . ' - ' . ($state['organization'] ?? '') ?? null)
                            ->default([]),
                    ]),

                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        TextInput::make('phone')
                            ->label('Telepon')
                            ->tel()
                            ->maxLength(50),
                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->maxLength(50),
                        Repeater::make('social')
                            ->label('Media Sosial')
                            ->schema([
                                TextInput::make('platform')
                                    ->label('Platform')
                                    ->required()
                                    ->maxLength(50),
                                TextInput::make('url')
                                    ->label('URL')
                                    ->url()
                                    ->required()
                                    ->maxLength(500),
                            ])
                            ->columns(2)
                            ->itemLabel(fn (array $state): ?string => $state['platform'] ?? null)
                            ->default([]),
                    ]),

                // ── Data Kepegawaian ─────────────────────────────────────
                Section::make('Data Kepegawaian')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('employment_status')->label('Status Kepegawaian'),
                        TextInput::make('ptk_type')->label('Jenis PTK'),
                        TextInput::make('sk_cpns')->label('SK CPNS'),
                        DatePicker::make('sk_cpns_date')->label('Tanggal SK CPNS')->native(false),
                        TextInput::make('sk_appointment')->label('SK Pengangkatan'),
                        DatePicker::make('joined_at')->label('TMT Pengangkatan')->native(false),
                        TextInput::make('appointing_agency')->label('Lembaga Pengangkatan'),
                        TextInput::make('rank_grade')->label('Pangkat / Golongan'),
                        TextInput::make('salary_source')->label('Sumber Gaji'),
                        DatePicker::make('civil_servant_start_date')->label('TMT PNS')->native(false),
                        TextInput::make('nuks')->label('NUKS'),
                        TextInput::make('years_of_service')->label('Masa Kerja (Tahun)')->numeric()->minValue(0),
                    ]),

                // ── Alamat ────────────────────────────────────────────────
                Section::make('Alamat')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Textarea::make('address')->label('Alamat Jalan')->rows(2)->columnSpanFull(),
                        TextInput::make('rt')->label('RT')->maxLength(8),
                        TextInput::make('rw')->label('RW')->maxLength(8),
                        TextInput::make('dusun')->label('Dusun / Lingkungan'),
                        TextInput::make('kelurahan')->label('Kelurahan / Desa'),
                        TextInput::make('kecamatan')->label('Kecamatan'),
                        TextInput::make('postal_code')->label('Kode Pos')->maxLength(10),
                        TextInput::make('phone_home')->label('No. Telepon Rumah')->tel()->maxLength(50),
                    ]),

                // ── Data Pribadi ──────────────────────────────────────────
                Section::make('Data Pribadi')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('religion')->label('Agama')->maxLength(32),
                        TextInput::make('nik')->label('NIK')->maxLength(20),
                        TextInput::make('kk_number')->label('No. KK')->maxLength(20),
                        TextInput::make('mother_name')->label('Nama Ibu Kandung'),
                        Select::make('marital_status')->label('Status Perkawinan')
                            ->options([
                                'Belum Kawin'   => 'Belum Kawin',
                                'Kawin'         => 'Kawin',
                                'Cerai Hidup'   => 'Cerai Hidup',
                                'Cerai Mati'    => 'Cerai Mati',
                            ]),
                        TextInput::make('spouse_name')->label('Nama Suami / Istri'),
                        TextInput::make('spouse_nip')->label('NIP Suami / Istri')->maxLength(50),
                        TextInput::make('spouse_occupation')->label('Pekerjaan Suami / Istri'),
                        TextInput::make('nationality')->label('Kewarganegaraan')->maxLength(32),
                    ]),

                // ── Kompetensi Khusus ─────────────────────────────────────
                Section::make('Kompetensi Khusus')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        Toggle::make('has_principal_license')->label('Sudah Lisensi Kepala Sekolah')->inline(false),
                        Toggle::make('has_supervision_training')->label('Pernah Diklat Kepengawasan')->inline(false),
                        Toggle::make('braille_skill')->label('Keahlian Braille')->inline(false),
                        Toggle::make('sign_language_skill')->label('Keahlian Bahasa Isyarat')->inline(false),
                    ]),

                // ── Pajak & Dokumen ───────────────────────────────────────
                Section::make('Pajak & Dokumen')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('npwp')->label('NPWP')->maxLength(32),
                        TextInput::make('taxpayer_name')->label('Nama Wajib Pajak'),
                        TextInput::make('karpeg')->label('Karpeg')->maxLength(32),
                        TextInput::make('karis_karsu')->label('Karis / Karsu')->maxLength(32),
                    ]),

                // ── Rekening Bank ─────────────────────────────────────────
                Section::make('Rekening Bank')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('bank_name')->label('Nama Bank'),
                        TextInput::make('bank_account_number')->label('No. Rekening'),
                        TextInput::make('bank_account_name')->label('Rekening Atas Nama'),
                    ]),

                // ── Koordinat GPS ─────────────────────────────────────────
                Section::make('Koordinat GPS')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('latitude')->label('Lintang')->numeric(),
                        TextInput::make('longitude')->label('Bujur')->numeric(),
                    ]),

                // ── Konten ────────────────────────────────────────────────
                Section::make('Konten')
                    ->schema([
                        FileUpload::make('photo')
                            ->label('Foto')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('staff-photos')
                            ->visibility('public')
                            ->maxSize(2048),
                        Textarea::make('bio')
                            ->label('Biografi')
                            ->rows(4),
                        TextInput::make('quote')
                            ->label('Kutipan')
                            ->maxLength(500),
                    ]),

                Section::make('Akun')
                    ->schema([
                        Select::make('user_id')
                            ->label('Akun User')
                            ->options(fn () => User::orderBy('email')->pluck('email', 'id'))
                            ->searchable()
                            ->nullable()
                            ->placeholder('Pilih akun login guru...'),
                    ]),

                Section::make('Pengaturan')
                    ->columns(2)
                    ->schema([
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
