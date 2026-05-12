<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesson_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('curriculum_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('curriculum_plan_topic_id')->nullable()->constrained('curriculum_plan_topics')->nullOnDelete();
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('period', 50)->nullable();
            $table->string('topic', 255);
            $table->text('learning_objectives')->nullable();
            $table->string('methods', 255)->nullable();
            $table->string('media', 255)->nullable();
            $table->text('assessment_plan')->nullable();
            $table->string('status', 16)->default('draft');
            $table->text('notes')->nullable();
            $table->datetime('actual_start_at')->nullable();
            $table->datetime('actual_end_at')->nullable();
            $table->integer('achievement_percent')->nullable();
            $table->text('execution_notes')->nullable();
            $table->text('homework_notes')->nullable();
            $table->json('student_work_links')->nullable();
            $table->text('issues_notes')->nullable();
            $table->datetime('completed_at')->nullable();
            $table->string('cancelled_reason', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['school_class_id', 'session_date'], 'idx_ls_class_date');
            $table->index(['staff_member_id', 'session_date'], 'idx_ls_staff_date');
            $table->index(['status', 'session_date'], 'idx_ls_status_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_sessions');
    }
};
