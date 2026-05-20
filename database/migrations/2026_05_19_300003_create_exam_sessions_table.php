<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exam_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->string('exam_type', 20); // uts, uas, pts, pas, remedial
            $table->string('title', 255);
            $table->date('exam_date');
            $table->string('academic_year', 20);
            $table->string('semester', 10);
            $table->decimal('max_score', 5, 2)->default(100);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['school_class_id', 'material_category_id', 'exam_type'], 'idx_es_class_mapel_type');
            $table->index(['academic_year', 'semester'], 'idx_es_year_sem');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_sessions');
    }
};
