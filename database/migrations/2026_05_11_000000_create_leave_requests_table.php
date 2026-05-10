<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('submitted_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('type', 16);                 // sakit | izin | dinas
            $table->date('date_from');
            $table->date('date_to');
            $table->text('reason');
            $table->string('attachment')->nullable();   // storage path
            $table->string('status', 16)->default('pending'); // pending | approved | rejected
            $table->string('submission_channel', 16)->default('portal'); // portal | public
            $table->string('submitter_name')->nullable();
            $table->string('submitter_phone', 32)->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_note')->nullable();
            $table->timestamp('notification_sent_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('status');
            $table->index('date_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_requests');
    }
};
