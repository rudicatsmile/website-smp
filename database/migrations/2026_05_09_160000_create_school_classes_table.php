<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('school_classes', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('grade'); // 7, 8, 9
            $table->string('section', 8); // A, B, C
            $table->string('name'); // "7A"
            $table->string('academic_year', 16); // "2025/2026"
            $table->foreignId('homeroom_teacher_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['grade', 'section', 'academic_year'], 'school_classes_unique_idx');
            $table->index(['grade', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_classes');
    }
};
