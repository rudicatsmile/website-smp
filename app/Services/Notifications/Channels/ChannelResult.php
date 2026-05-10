<?php

declare(strict_types=1);

namespace App\Services\Notifications\Channels;

class ChannelResult
{
    public function __construct(
        public bool $success,
        public ?array $payload = null,
        public ?string $error = null,
    ) {}

    public static function ok(?array $payload = null): self
    {
        return new self(true, $payload);
    }

    public static function fail(string $error, ?array $payload = null): self
    {
        return new self(false, $payload, $error);
    }
}
