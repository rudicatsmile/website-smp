<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bank_question_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 16)->default('mcq');
            $table->longText('body');
            $table->longText('explanation')->nullable();
            $table->unsignedInteger('score')->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index(['quiz_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
