<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('lesson_session_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_material_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['lesson_session_id', 'class_material_id'], 'uq_lsm_session_material');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_session_materials');
    }
};
