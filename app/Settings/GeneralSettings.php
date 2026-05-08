<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class GeneralSettings extends Settings
{
    public string $school_name;
    public ?string $tagline = null;
    public ?string $logo = null;
    public ?string $favicon = null;
    public ?string $address = null;
    public ?string $phone = null;
    public ?string $email = null;
    public ?string $whatsapp = null;
    public ?string $maps_embed = null;
    public ?string $facebook = null;
    public ?string $instagram = null;
    public ?string $youtube = null;
    public ?string $tiktok = null;
    public ?string $meta_title = null;
    public ?string $meta_description = null;
    public ?string $og_image = null;
    public ?string $footer_text = null;
    public ?string $copyright = null;

    public static function group(): string
    {
        return 'general';
    }
}
