<?php

declare(strict_types=1);

namespace App\Observers;

use App\Mail\SpmbStatusChanged;
use App\Models\SpmbRegistration;
use Illuminate\Support\Facades\Mail;

class SpmbRegistrationObserver
{
    public function updated(SpmbRegistration $registration): void
    {
        if ($registration->wasChanged('status') && $registration->email) {
            try {
                Mail::to($registration->email)->send(new SpmbStatusChanged($registration));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
