<?php

declare(strict_types=1);

namespace App\Services\Notifications\Channels;

use App\Mail\GenericNotificationMail;
use Illuminate\Support\Facades\Mail;

class MailChannel
{
    public function send(string $email, string $subject, string $body): ChannelResult
    {
        try {
            Mail::to($email)->send(new GenericNotificationMail($subject, $body));
        } catch (\Throwable $e) {
            return ChannelResult::fail('Mail error: ' . $e->getMessage());
        }

        return ChannelResult::ok(['driver' => config('mail.default')]);
    }
}
