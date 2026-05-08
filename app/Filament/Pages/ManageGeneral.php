<?php

namespace App\Filament\Pages;

use App\Settings\GeneralSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageGeneral extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-general';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Pengaturan Umum';

    protected static ?string $title = 'Pengaturan Umum';

    protected static ?int $navigationSort = 4;

    public ?array $data = [];

    public function mount(): void
    {
        $s = app(GeneralSettings::class);

        $this->form->fill([
            'school_name' => $s->school_name,
            'tagline' => $s->tagline,
            'logo' => $s->logo,
            'favicon' => $s->favicon,
            'address' => $s->address,
            'phone' => $s->phone,
            'whatsapp' => $s->whatsapp,
            'email' => $s->email,
            'maps_embed' => $s->maps_embed,
            'facebook' => $s->facebook,
            'instagram' => $s->instagram,
            'youtube' => $s->youtube,
            'tiktok' => $s->tiktok,
            'meta_title' => $s->meta_title,
            'meta_description' => $s->meta_description,
            'og_image' => $s->og_image,
            'footer_text' => $s->footer_text,
            'copyright' => $s->copyright,
            'active_skin' => $s->active_skin ?: 'education',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Skin / Tema Frontend')
                    ->description('Pilih tampilan visual yang digunakan di seluruh halaman frontend. Perubahan langsung berlaku setelah disimpan.')
                    ->schema([
                        Select::make('active_skin')
                            ->label('Skin Aktif')
                            ->options([
                                'education' => '🎓 Education Profesional — Elegan, formal, hijau emerald',
                                'milleneal' => '✨ Milleneal — Modern, vibrant, gradient pink-purple',
                            ])
                            ->required()
                            ->native(false)
                            ->helperText('Skin baru dapat ditambahkan dengan membuat folder di resources/views/skins/{nama}.'),
                    ]),

                Section::make('Identitas Sekolah')
                    ->columns(2)
                    ->schema([
                        TextInput::make('school_name')
                            ->label('Nama Sekolah')
                            ->required()
                            ->maxLength(150),
                        TextInput::make('tagline')
                            ->label('Tagline')
                            ->maxLength(200),
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('general')
                            ->maxSize(1024),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('general')
                            ->maxSize(512),
                    ]),

                Section::make('Kontak')
                    ->columns(2)
                    ->schema([
                        Textarea::make('address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('phone')->label('Telepon'),
                        TextInput::make('whatsapp')->label('WhatsApp'),
                        TextInput::make('email')->label('Email')->email(),
                        Textarea::make('maps_embed')
                            ->label('Embed Google Maps (iframe)')
                            ->rows(3)
                            ->columnSpanFull(),
                    ]),

                Section::make('Media Sosial')
                    ->columns(2)
                    ->schema([
                        TextInput::make('facebook')->label('Facebook URL')->url(),
                        TextInput::make('instagram')->label('Instagram URL')->url(),
                        TextInput::make('youtube')->label('YouTube URL')->url(),
                        TextInput::make('tiktok')->label('TikTok URL')->url(),
                    ]),

                Section::make('SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(150),
                        FileUpload::make('og_image')
                            ->label('OG Image')
                            ->image()
                            ->disk('public')
                            ->directory('general')
                            ->maxSize(2048),
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->rows(3)
                            ->maxLength(300)
                            ->columnSpanFull(),
                    ]),

                Section::make('Footer')
                    ->columns(2)
                    ->schema([
                        Textarea::make('footer_text')
                            ->label('Footer Text')
                            ->rows(2)
                            ->columnSpanFull(),
                        TextInput::make('copyright')
                            ->label('Copyright')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $s = app(GeneralSettings::class);

        foreach ($data as $key => $value) {
            if (property_exists($s, $key)) {
                $s->{$key} = $value;
            }
        }
        $s->save();

        Notification::make()
            ->title('Pengaturan umum berhasil diperbarui')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }
}
