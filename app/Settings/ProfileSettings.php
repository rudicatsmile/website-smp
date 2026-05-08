<?php

declare(strict_types=1);

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ProfileSettings extends Settings
{
    public ?string $history = null;
    public ?string $vision = null;
    public ?string $mission = null;
    public ?string $principal_message = null;
    public ?string $principal_name = null;
    public ?string $principal_photo = null;
    public ?string $organization_image = null;

    public static function group(): string
    {
        return 'profile';
    }
}
