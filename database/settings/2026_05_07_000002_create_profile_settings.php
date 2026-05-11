<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('profile.history', '<p>Sejarah singkat SMP Al Wathoniyah 9...</p>');
        $this->migrator->add('profile.vision', 'Menjadi sekolah unggulan berbasis akhlak dan prestasi.');
        $this->migrator->add('profile.mission', "<ul><li>Mendidik dengan akhlak mulia</li><li>Menumbuhkan budaya prestasi</li></ul>");
        $this->migrator->add('profile.principal_message', '<p>Selamat datang di website resmi SMP Al Wathoniyah 9.</p>');
        $this->migrator->add('profile.principal_name', 'Kepala Sekolah');
        $this->migrator->add('profile.principal_photo', null);
        $this->migrator->add('profile.organization_image', null);
    }
};
