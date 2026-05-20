<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('session_assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_assessment_id')->constrained('session_assessments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('score', 5, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['session_assessment_id', 'student_id'], 'uq_sas_assessment_student');
            $table->index('student_id', 'idx_sas_student');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_assessment_scores');
    }
};
