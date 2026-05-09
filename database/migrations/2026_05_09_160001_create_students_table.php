<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('school_class_id')->nullable()->constrained('school_classes')->nullOnDelete();
            $table->string('nis', 32)->unique();
            $table->string('nisn', 32)->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('gender', 8)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('birth_place')->nullable();
            $table->string('photo')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone', 32)->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('school_class_id');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
