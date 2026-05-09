<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bank_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_bank_id')->constrained()->cascadeOnDelete();
            $table->string('type', 16)->default('mcq'); // mcq | multi | essay
            $table->longText('body');
            $table->longText('explanation')->nullable();
            $table->unsignedInteger('score')->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index(['question_bank_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_questions');
    }
};
