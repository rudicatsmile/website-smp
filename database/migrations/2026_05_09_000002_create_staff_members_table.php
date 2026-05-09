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
        Schema::create('staff_members', function (Blueprint $table) {
            $table->id();
            // Identitas
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('nip')->nullable();
            $table->string('nuptk')->nullable();
            $table->enum('gender', ['L', 'P'])->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();

            // Jabatan
            $table->foreignId('staff_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('position')->nullable();
            $table->boolean('is_principal')->default(false);
            $table->date('joined_at')->nullable();
            $table->unsignedInteger('years_of_service')->nullable();

            // Akademik (JSON)
            $table->json('subjects')->nullable();
            $table->json('education')->nullable();
            $table->json('certifications')->nullable();
            $table->json('experiences')->nullable();

            // Kontak & Profil
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->json('social')->nullable();

            // Konten
            $table->string('photo')->nullable();
            $table->text('bio')->nullable();
            $table->string('quote')->nullable();

            // Meta
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'is_principal', 'order']);
            $table->index('staff_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_members');
    }
};
