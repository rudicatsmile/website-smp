<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->string('scope', 16)->default('assigned'); // assigned | public
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->unsignedInteger('max_attempts')->default(1);
            $table->boolean('shuffle_questions')->default(true);
            $table->boolean('shuffle_options')->default(true);
            $table->boolean('show_explanation')->default(true);
            $table->boolean('show_score_immediately')->default(true);
            $table->dateTime('opens_at')->nullable();
            $table->dateTime('closes_at')->nullable();
            $table->unsignedInteger('total_score')->default(0);
            $table->boolean('is_published')->default(false);
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->index(['scope', 'is_published']);
            $table->index(['school_class_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
