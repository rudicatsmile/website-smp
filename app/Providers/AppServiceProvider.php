<?php

namespace App\Providers;

use App\Models\ClassAnnouncement;
use App\Models\InternalAnnouncement;
use App\Models\SpmbRegistration;
use App\Models\StudentAttendance;
use App\Observers\ClassAnnouncementObserver;
use App\Observers\InternalAnnouncementObserver;
use App\Observers\SpmbRegistrationObserver;
use App\Observers\StudentAttendanceObserver;
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
        StudentAttendance::observe(StudentAttendanceObserver::class);
        ClassAnnouncement::observe(ClassAnnouncementObserver::class);
    }
}
