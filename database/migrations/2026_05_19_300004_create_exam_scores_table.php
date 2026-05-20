<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_session_id')->constrained('exam_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->boolean('is_remedial')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['exam_session_id', 'student_id'], 'uq_es_exam_student');
            $table->index('student_id', 'idx_es_student');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_scores');
    }
};
