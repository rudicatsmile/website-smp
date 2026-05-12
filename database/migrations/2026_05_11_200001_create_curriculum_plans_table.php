<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curriculum_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_class_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained()->nullOnDelete();
            $table->string('academic_year', 20);
            $table->string('semester', 10);
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('default_methods', 255)->nullable();
            $table->string('default_media', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['school_class_id', 'material_category_id', 'academic_year', 'semester'], 'uq_curriculum_plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_plans');
    }
};
