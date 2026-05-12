<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesson_session_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_assignment_id')->constrained()->cascadeOnDelete();
            $table->datetime('given_at')->nullable();
            $table->timestamps();

            $table->unique(['lesson_session_id', 'class_assignment_id'], 'uq_lsa_session_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_session_assignments');
    }
};
