<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('notifiable');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone', 32)->nullable();
            $table->string('recipient_email')->nullable();
            $table->string('channel', 16);           // whatsapp | email
            $table->string('event', 32);             // absensi | payment_due | announcement | manual | rapor
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->string('status', 16)->default('pending'); // pending | sent | failed
            $table->text('error')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('triggered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['channel', 'status']);
            $table->index(['event', 'status']);
            $table->index('sent_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
