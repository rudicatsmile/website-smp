<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_announcements', function (Blueprint $table) {
            $table->boolean('notify_wa')->default(false)->after('is_published');
            $table->boolean('notify_email')->default(false)->after('notify_wa');
            $table->timestamp('notification_sent_at')->nullable()->after('notify_email');
        });
    }

    public function down(): void
    {
        Schema::table('class_announcements', function (Blueprint $table) {
            $table->dropColumn(['notify_wa', 'notify_email', 'notification_sent_at']);
        });
    }
};
