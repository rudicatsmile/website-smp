<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('page_heroes', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('title');
            $table->text('subtitle')->nullable();
            $table->string('icon')->nullable();
            $table->string('background_image')->nullable();
            $table->string('overlay_from')->default('emerald-600');
            $table->string('overlay_via')->nullable()->default('emerald-700');
            $table->string('overlay_to')->default('teal-800');
            $table->unsignedTinyInteger('overlay_opacity')->default(100);
            $table->boolean('show_breadcrumb')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_heroes');
    }
};
