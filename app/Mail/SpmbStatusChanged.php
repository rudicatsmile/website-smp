<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\SpmbRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SpmbStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public SpmbRegistration $registration) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Update Status Pendaftaran SPMB - '.$this->registration->registration_number);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.spmb-status-changed');
    }
}
