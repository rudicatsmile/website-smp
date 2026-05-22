<?php

declare(strict_types=1);

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use App\Filament\Concerns\HidesFromEkskulRole;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Services\Notifications\NotificationService;

class StudentResource extends Resource
{
    use HidesFromEkskulRole;

    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Siswa';

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?int $navigationSort = 11;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([

            // ── Identitas Pokok ─────────────────────────────────────────
            Section::make('Identitas Pokok')->columns(2)->schema([
                TextInput::make('nis')->label('NIS / NIPD')->required()->unique(ignoreRecord: true)->maxLength(32),
                TextInput::make('nisn')->label('NISN')->maxLength(32),
                TextInput::make('name')->label('Nama Lengkap')->required()->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug((string) $state))),
                TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true)->maxLength(255),
                Select::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::active()->ordered()->pluck('name', 'id'))
                    ->searchable()->preload()->required(),
                Select::make('gender')->label('Jenis Kelamin')->options(Student::GENDERS),
                DatePicker::make('birth_date')->label('Tanggal Lahir')->native(false),
                TextInput::make('birth_place')->label('Tempat Lahir')->maxLength(255),
                FileUpload::make('photo')->label('Foto')->image()->disk('public')->directory('students')
                    ->maxSize(2048)->columnSpanFull(),
            ]),

            // ── Data Kependudukan ────────────────────────────────────────
            Section::make('Data Kependudukan')->columns(2)->schema([
                TextInput::make('nik')->label('NIK')->maxLength(20),
                TextInput::make('religion')->label('Agama')->maxLength(32),
                TextInput::make('kk_number')->label('No. KK')->maxLength(20),
                TextInput::make('birth_certificate_number')->label('No. Registrasi Akta Lahir'),
                TextInput::make('skhun')->label('SKHUN'),
            ])->collapsed(),

            // ── Alamat & Kontak ──────────────────────────────────────────
            Section::make('Alamat & Kontak')->columns(2)->schema([
                Textarea::make('address')->label('Alamat Jalan')->rows(2)->columnSpanFull(),
                TextInput::make('rt')->label('RT')->maxLength(8),
                TextInput::make('rw')->label('RW')->maxLength(8),
                TextInput::make('dusun')->label('Dusun / Lingkungan'),
                TextInput::make('kelurahan')->label('Kelurahan / Desa'),
                TextInput::make('kecamatan')->label('Kecamatan'),
                TextInput::make('postal_code')->label('Kode Pos')->maxLength(10),
                Select::make('living_with')->label('Jenis Tinggal')
                    ->options([
                        'Bersama orang tua' => 'Bersama Orang Tua',
                        'Wali'              => 'Wali',
                        'Kos'               => 'Kos / Kontrak',
                        'Asrama'            => 'Asrama',
                        'Panti'             => 'Panti Asuhan',
                        'Lainnya'           => 'Lainnya',
                    ])->searchable(),
                Select::make('transportation')->label('Alat Transportasi')
                    ->options([
                        'Jalan kaki'                    => 'Jalan Kaki',
                        'Sepeda'                        => 'Sepeda',
                        'Sepeda motor'                  => 'Sepeda Motor',
                        'Angkutan umum/bus/pete-pete'   => 'Angkutan Umum / Bus',
                        'Ojek'                          => 'Ojek',
                        'Andong/Bendi/Sado/Dokar/Delman/Becak' => 'Andong / Becak',
                        'Mobil/Bus Sekolah'             => 'Mobil / Bus Sekolah',
                        'Perahu/Sampan'                 => 'Perahu / Sampan',
                        'Lainnya'                       => 'Lainnya',
                    ])->searchable(),
                TextInput::make('phone')->label('No. Telepon Rumah')->tel()->maxLength(32),
            ])->collapsed(),

            // ── Data Ayah ────────────────────────────────────────────────
            Section::make('Data Ayah')->columns(2)->schema([
                TextInput::make('parent_name')->label('Nama Ayah'),
                TextInput::make('father_birth_year')->label('Tahun Lahir Ayah')->numeric()->minValue(1900)->maxValue(2010),
                TextInput::make('father_education')->label('Jenjang Pendidikan'),
                TextInput::make('father_occupation')->label('Pekerjaan'),
                TextInput::make('father_income')->label('Penghasilan'),
                TextInput::make('father_nik')->label('NIK Ayah')->maxLength(20),
            ])->collapsed(),

            // ── Data Ibu ─────────────────────────────────────────────────
            Section::make('Data Ibu')->columns(2)->schema([
                TextInput::make('mother_name')->label('Nama Ibu'),
                TextInput::make('mother_birth_year')->label('Tahun Lahir Ibu')->numeric()->minValue(1900)->maxValue(2010),
                TextInput::make('mother_education')->label('Jenjang Pendidikan'),
                TextInput::make('mother_occupation')->label('Pekerjaan'),
                TextInput::make('mother_income')->label('Penghasilan'),
                TextInput::make('mother_nik')->label('NIK Ibu')->maxLength(20),
                TextInput::make('mother_phone')->label('No. HP Ibu')->tel()
                    ->helperText('Format Indonesia (08xx atau 628xx) untuk notifikasi WhatsApp'),
            ])->collapsed(),

            // ── Data Wali ────────────────────────────────────────────────
            Section::make('Data Wali')->columns(2)->schema([
                TextInput::make('guardian_name')->label('Nama Wali'),
                TextInput::make('guardian_birth_year')->label('Tahun Lahir Wali')->numeric()->minValue(1900)->maxValue(2010),
                TextInput::make('guardian_education')->label('Jenjang Pendidikan'),
                TextInput::make('guardian_occupation')->label('Pekerjaan'),
                TextInput::make('guardian_income')->label('Penghasilan'),
                TextInput::make('guardian_nik')->label('NIK Wali')->maxLength(20),
            ])->collapsed(),

            // ── Kontak Orang Tua ─────────────────────────────────────────
            Section::make('Kontak & Email Orang Tua')->columns(2)->schema([
                TextInput::make('parent_phone')->label('No. HP Ayah / Wali')->tel()
                    ->helperText('Format Indonesia (08xx atau 628xx) untuk notifikasi WhatsApp'),
                TextInput::make('parent_email')->label('Email Orang Tua')->email()
                    ->helperText('Untuk notifikasi email otomatis'),
            ])->collapsed(),

            // ── Data Sekolah & Dokumen ───────────────────────────────────
            Section::make('Data Sekolah & Dokumen')->columns(2)->schema([
                TextInput::make('previous_school')->label('Sekolah Asal')->columnSpanFull(),
                TextInput::make('child_order')->label('Anak Ke-')->numeric()->minValue(1)->maxValue(20),
                TextInput::make('un_number')->label('No. Peserta Ujian Nasional'),
                TextInput::make('certificate_number')->label('No. Seri Ijazah'),
            ])->collapsed(),

            // ── Bantuan Sosial ───────────────────────────────────────────
            Section::make('Bantuan Sosial')->columns(2)->schema([
                Toggle::make('kps_recipient')->label('Penerima KPS')->inline(false),
                TextInput::make('kps_number')->label('No. KPS'),
                Toggle::make('kip_recipient')->label('Penerima KIP')->inline(false),
                TextInput::make('kip_number')->label('Nomor KIP'),
                TextInput::make('kip_name')->label('Nama di KIP'),
                TextInput::make('kks_number')->label('Nomor KKS'),
                Toggle::make('pip_eligible')->label('Layak PIP (usulan sekolah)')->inline(false),
                TextInput::make('pip_reason')->label('Alasan Layak PIP'),
            ])->collapsed(),

            // ── Rekening Bank ────────────────────────────────────────────
            Section::make('Rekening Bank')->columns(2)->schema([
                TextInput::make('bank_name')->label('Nama Bank'),
                TextInput::make('bank_account_number')->label('No. Rekening'),
                TextInput::make('bank_account_name')->label('Rekening Atas Nama'),
            ])->collapsed(),

            // ── Data Fisik ───────────────────────────────────────────────
            Section::make('Data Fisik & Lainnya')->columns(3)->schema([
                TextInput::make('weight')->label('Berat Badan (kg)')->numeric()->suffix('kg'),
                TextInput::make('height')->label('Tinggi Badan (cm)')->numeric()->suffix('cm'),
                TextInput::make('head_circumference')->label('Lingkar Kepala (cm)')->numeric()->suffix('cm'),
                TextInput::make('siblings_count')->label('Jml. Saudara Kandung')->numeric(),
                TextInput::make('home_distance')->label('Jarak Rumah ke Sekolah (km)')->numeric()->suffix('km'),
                TextInput::make('special_needs')->label('Kebutuhan Khusus'),
            ])->collapsed(),

            // ── Koordinat GPS ────────────────────────────────────────────
            Section::make('Koordinat GPS')->columns(2)->schema([
                TextInput::make('latitude')->label('Lintang')->numeric(),
                TextInput::make('longitude')->label('Bujur')->numeric(),
            ])->collapsed(),

            // ── Akun Login ───────────────────────────────────────────────
            Section::make('Akun Login')->columns(2)->schema([
                Select::make('user_id')->label('Akun User')
                    ->options(fn () => User::query()->orderBy('name')->pluck('name', 'id'))
                    ->searchable()->placeholder('— belum ada akun —')
                    ->helperText('Gunakan tombol "Generate Akun" di tabel untuk membuat otomatis.'),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo')->label('Foto')->disk('public')->circular(),
                TextColumn::make('nis')->label('NIS')->searchable()->sortable(),
                TextColumn::make('name')->label('Nama')->searchable()->sortable(),
                TextColumn::make('schoolClass.name')->label('Kelas')->badge()->sortable(),
                TextColumn::make('gender')->label('JK')
                    ->formatStateUsing(fn ($state) => Student::GENDERS[$state] ?? $state),
                TextColumn::make('user.email')->label('Email Login')->placeholder('— belum login —')->toggleable(),
                TextColumn::make('qr_token')->label('QR Token')->copyable()->placeholder('—')
                    ->fontFamily('mono')->size('xs')->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')->label('Aktif')->boolean(),
            ])
            ->filters([
                SelectFilter::make('school_class_id')->label('Kelas')
                    ->options(fn () => SchoolClass::ordered()->pluck('name', 'id')),
                SelectFilter::make('gender')->options(Student::GENDERS),
                TernaryFilter::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('generate_account')
                    ->label('Generate Akun')
                    ->icon('heroicon-o-key')
                    ->color('primary')
                    ->visible(fn ($record) => $record->user_id === null)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $email = strtolower($record->nis) . '@siswa.smpalwathoniyah9.sch.id';
                        $user = User::firstOrCreate(
                            ['email' => $email],
                            ['name' => $record->name, 'password' => Hash::make('siswa123'), 'is_active' => true]
                        );
                        if (! $user->hasRole('student')) {
                            $user->assignRole('student');
                        }
                        $record->update(['user_id' => $user->id]);
                        Notification::make()
                            ->title('Akun siswa dibuat')
                            ->body("Email: {$email} | Password: siswa123")
                            ->success()
                            ->send();
                    }),
                Action::make('send_notification')
                    ->label('Kirim Notifikasi')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn ($record) => filled($record->parent_phone) || filled($record->parent_email))
                    ->schema([
                        CheckboxList::make('channels')
                            ->label('Kanal')
                            ->options(['whatsapp' => 'WhatsApp', 'email' => 'Email'])
                            ->default(['whatsapp'])
                            ->required()
                            ->columns(2),
                        \Filament\Forms\Components\TextInput::make('subject')
                            ->label('Subjek (untuk email)')
                            ->default(fn ($record) => 'Pemberitahuan untuk ' . $record->name)
                            ->maxLength(180),
                        \Filament\Forms\Components\Textarea::make('message')
                            ->label('Isi Pesan')
                            ->required()
                            ->rows(6)
                            ->placeholder('Tulis pesan untuk orang tua...'),
                    ])
                    ->action(function ($record, array $data) {
                        /** @var NotificationService $service */
                        $service = app(NotificationService::class);
                        $logs = $service->sendCustom(
                            recipient: [
                                'name'  => $record->parent_name,
                                'phone' => $record->parent_phone,
                                'email' => $record->parent_email,
                            ],
                            channels: $data['channels'] ?? [],
                            subject: $data['subject'] ?? 'Pemberitahuan',
                            body: $data['message'],
                            event: 'manual',
                            notifiable: $record,
                            triggeredBy: auth()->id(),
                        );
                        Notification::make()
                            ->title('Notifikasi diantrekan')
                            ->body(count($logs) . ' pesan masuk antrian pengiriman.')
                            ->success()
                            ->send();
                    }),
                Action::make('regenerate_qr')
                    ->label('Regenerate QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate QR Token')
                    ->modalDescription('Token lama akan tidak berlaku lagi. Lanjutkan?')
                    ->action(function ($record) {
                        $record->generateQrToken(force: true);
                        Notification::make()->title('QR token diperbarui')
                            ->body('Token baru: ' . $record->fresh()->qr_token)
                            ->success()->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin']) ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
