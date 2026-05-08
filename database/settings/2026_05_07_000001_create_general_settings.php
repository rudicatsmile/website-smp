<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.school_name', 'SMP Al Wahoniyah 9');
        $this->migrator->add('general.tagline', 'Mendidik Generasi Berakhlak & Berprestasi');
        $this->migrator->add('general.logo', null);
        $this->migrator->add('general.favicon', null);
        $this->migrator->add('general.address', 'Jl. Contoh No. 9, Kota');
        $this->migrator->add('general.phone', '021-0000000');
        $this->migrator->add('general.email', 'info@smpalwahoniyah9.sch.id');
        $this->migrator->add('general.whatsapp', '6281200000000');
        $this->migrator->add('general.maps_embed', null);
        $this->migrator->add('general.facebook', null);
        $this->migrator->add('general.instagram', null);
        $this->migrator->add('general.youtube', null);
        $this->migrator->add('general.tiktok', null);
        $this->migrator->add('general.meta_title', 'SMP Al Wahoniyah 9');
        $this->migrator->add('general.meta_description', 'Website resmi SMP Al Wahoniyah 9');
        $this->migrator->add('general.og_image', null);
        $this->migrator->add('general.footer_text', 'SMP Al Wahoniyah 9 - Mendidik dengan hati.');
        $this->migrator->add('general.copyright', '© ' . date('Y') . ' SMP Al Wahoniyah 9. All rights reserved.');
    }
};
