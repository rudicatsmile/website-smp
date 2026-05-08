<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('download_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        Schema::create('downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('download_categories')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('file');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->boolean('is_public')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('downloads');
        Schema::dropIfExists('download_categories');
    }
};
