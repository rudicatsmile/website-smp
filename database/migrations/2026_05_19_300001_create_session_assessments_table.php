<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('session_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_session_id')->constrained('lesson_sessions')->cascadeOnDelete();
            $table->string('title', 200);
            $table->string('type', 30)->default('kuis'); // kuis, ulangan_harian, tugas_kelas
            $table->decimal('max_score', 5, 2)->default(100);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('lesson_session_id', 'idx_sa_session');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_assessments');
    }
};
