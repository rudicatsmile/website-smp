<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained()->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('description')->nullable();

            $table->string('type')->default('modul_ajar');
            $table->string('grade')->default('umum');
            $table->string('phase')->nullable();
            $table->string('curriculum')->default('merdeka');
            $table->string('semester')->default('tahunan');
            $table->string('academic_year')->nullable();

            $table->string('file_path');
            $table->string('file_name')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('file_mime')->nullable();
            $table->string('cover_image')->nullable();

            $table->json('tags')->nullable();

            $table->boolean('is_public')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);

            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);

            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'is_public', 'published_at']);
            $table->index(['material_category_id', 'grade', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
