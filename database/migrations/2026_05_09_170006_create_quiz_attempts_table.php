<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('attempt_no')->default(1);
            $table->dateTime('started_at')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->unsignedInteger('score')->nullable();
            $table->unsignedInteger('max_score')->default(0);
            $table->boolean('is_graded')->default(false);
            $table->foreignId('graded_by')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->dateTime('graded_at')->nullable();
            $table->timestamps();
            $table->unique(['quiz_id', 'student_id', 'attempt_no']);
            $table->index(['quiz_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
