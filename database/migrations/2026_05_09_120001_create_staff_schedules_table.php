<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('material_category_id')->nullable()->constrained()->nullOnDelete();

            $table->string('type')->default('mengajar');
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');

            $table->string('period')->nullable();
            $table->string('class_name')->nullable();
            $table->string('location')->nullable();
            $table->string('notes')->nullable();
            $table->string('color')->default('emerald');

            $table->date('effective_from')->nullable();
            $table->date('effective_until')->nullable();
            $table->string('academic_year')->nullable();
            $table->string('semester')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['day_of_week', 'start_time']);
            $table->index(['staff_member_id', 'day_of_week']);
            $table->index(['type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_schedules');
    }
};
