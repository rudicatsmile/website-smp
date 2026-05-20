<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesson_session_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_session_id')->constrained('lesson_sessions')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->text('problem');
            $table->string('status', 20)->default('tidak_selesai'); // selesai | tidak_selesai
            $table->text('follow_up')->nullable();
            $table->timestamps();

            $table->index('lesson_session_id', 'idx_lsc_session');
            $table->index('student_id',         'idx_lsc_student');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_session_cases');
    }
};
