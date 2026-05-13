<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumni', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->smallInteger('graduation_year');
            $table->string('current_status')->default('working');
            // working | studying | entrepreneur | both | other
            $table->string('company_or_institution')->nullable();
            $table->string('position')->nullable();
            $table->string('city')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('quote', 500)->nullable();
            $table->longText('story')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->boolean('is_published')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->index(['is_published', 'is_featured', 'order']);
            $table->index('graduation_year');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumni');
    }
};
