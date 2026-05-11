<?php

namespace App\Filament\Pages;

use App\Settings\ProfileSettings;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.manage-profile';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Profil Sekolah';

    protected static ?string $title = 'Profil Sekolah';

    protected static ?int $navigationSort = 2;

    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(ProfileSettings::class);

        $this->form->fill([
            'history' => $settings->history,
            'vision' => $settings->vision,
            'mission' => $settings->mission,
            'principal_name' => $settings->principal_name,
            'principal_photo' => $settings->principal_photo,
            'principal_message' => $settings->principal_message,
            'organization_image' => $settings->organization_image,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Sejarah')
                    ->schema([
                        RichEditor::make('history')
                            ->label('Sejarah Sekolah')
                            ->columnSpanFull(),
                    ]),

                Section::make('Visi & Misi')
                    ->columns(2)
                    ->schema([
                        Textarea::make('vision')
                            ->label('Visi')
                            ->rows(5)
                            ->columnSpan(1),
                        RichEditor::make('mission')
                            ->label('Misi')
                            ->columnSpan(1),
                    ]),

                Section::make('Sambutan Kepala Sekolah')
                    ->columns(2)
                    ->schema([
                        TextInput::make('principal_name')
                            ->label('Nama Kepala Sekolah')
                            ->maxLength(150),
                        FileUpload::make('principal_photo')
                            ->label('Foto Kepala Sekolah')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('profile')
                            ->visibility('public')
                            ->maxSize(2048),
                        RichEditor::make('principal_message')
                            ->label('Pesan / Sambutan')
                            ->columnSpanFull(),
                    ]),

                Section::make('Struktur Organisasi')
                    ->schema([
                        FileUpload::make('organization_image')
                            ->label('Gambar Struktur Organisasi')
                            ->image()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('profile')
                            ->visibility('public')
                            ->maxSize(3072)
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $settings = app(ProfileSettings::class);
        $settings->history = $data['history'] ?? null;
        $settings->vision = $data['vision'] ?? null;
        $settings->mission = $data['mission'] ?? null;
        $settings->principal_name = $data['principal_name'] ?? null;
        $settings->principal_photo = $data['principal_photo'] ?? null;
        $settings->principal_message = $data['principal_message'] ?? null;
        $settings->organization_image = $data['organization_image'] ?? null;
        $settings->save();

        Notification::make()
            ->title('Profil sekolah berhasil diperbarui')
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
