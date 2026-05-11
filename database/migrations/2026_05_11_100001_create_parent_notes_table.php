<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parent_notes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->unique();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->foreignId('initiator_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('initiator_type', 16)->default('parent');
            $table->string('subject', 200);
            $table->string('category', 32)->default('akademik');
            $table->string('priority', 16)->default('medium');
            $table->string('status', 16)->default('open');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['homeroom_teacher_id', 'status']);
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_notes');
    }
};
