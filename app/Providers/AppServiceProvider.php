<?php

namespace App\Providers;

use App\Models\InternalAnnouncement;
use App\Models\SpmbRegistration;
use App\Observers\InternalAnnouncementObserver;
use App\Observers\SpmbRegistrationObserver;
use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        SpmbRegistration::observe(SpmbRegistrationObserver::class);
        InternalAnnouncement::observe(InternalAnnouncementObserver::class);
    }
}
