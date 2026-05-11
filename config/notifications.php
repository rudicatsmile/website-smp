<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | School Identity (used inside message templates)
    |--------------------------------------------------------------------------
    */
    'school_name' => env('NOTIF_SCHOOL_NAME', 'SMP Al Wathoniyah 9'),
    'school_phone' => env('NOTIF_SCHOOL_PHONE', ''),
    'school_website' => env('NOTIF_SCHOOL_WEBSITE', config('app.url')),

    /*
    |--------------------------------------------------------------------------
    | Default enabled channels per event. Set to false to disable globally.
    |--------------------------------------------------------------------------
    */
    'events' => [
        'absensi' => [
            'enabled' => (bool) env('NOTIF_ABSENSI_ENABLED', true),
            'channels' => ['whatsapp', 'email'],
            // Only notify when attendance status is one of these
            'statuses' => ['alpa', 'sakit', 'izin', 'terlambat'],
        ],
        'payment_due' => [
            'enabled' => (bool) env('NOTIF_PAYMENT_ENABLED', true),
            'channels' => ['whatsapp', 'email'],
            // Days before due date to send reminders; 0 = due today, negative = overdue
            'reminder_days' => [3, 1, 0, -3],
        ],
        'announcement' => [
            'enabled' => (bool) env('NOTIF_ANNOUNCEMENT_ENABLED', true),
            'channels' => ['whatsapp', 'email'],
        ],
        'leave_request' => [
            'enabled' => (bool) env('NOTIF_LEAVE_REQUEST', true),
            'channels' => ['whatsapp', 'email'],
            // Skip weekends when auto-creating attendance rows on approval
            'skip_weekends' => (bool) env('NOTIF_LEAVE_SKIP_WEEKEND', true),
        ],
        'parent_note' => [
            'enabled' => (bool) env('NOTIF_PARENT_NOTE', true),
            'channels' => ['whatsapp', 'email'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue configuration for notification jobs
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'connection' => env('NOTIF_QUEUE_CONNECTION', config('queue.default')),
        'name' => env('NOTIF_QUEUE_NAME', 'notifications'),
        'retries' => 3,
        'retry_after_seconds' => 60,
    ],

    /*
    |--------------------------------------------------------------------------
    | Wablas driver options. Set to 'log' to only log messages instead of
    | calling the real API (useful for local dev when WABLAS_TOKEN is empty).
    |--------------------------------------------------------------------------
    */
    'wablas' => [
        'driver' => env('WABLAS_DRIVER', 'http'), // http | log
        'delay_ms_between_messages' => (int) env('WABLAS_DELAY_MS', 800),
    ],

];
