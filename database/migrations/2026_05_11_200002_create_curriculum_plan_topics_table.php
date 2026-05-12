<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('curriculum_plan_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_plan_id')->constrained()->cascadeOnDelete();
            $table->integer('week_number');
            $table->integer('order')->default(0);
            $table->string('topic', 255);
            $table->text('learning_objectives')->nullable();
            $table->string('methods', 255)->nullable();
            $table->string('media', 255)->nullable();
            $table->text('assessment_plan')->nullable();
            $table->integer('default_duration_minutes')->default(90);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['curriculum_plan_id', 'week_number', 'order'], 'idx_cpt_plan_week_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculum_plan_topics');
    }
};
