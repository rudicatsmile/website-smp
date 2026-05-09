<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body')->nullable();
            $table->string('category')->default('umum');
            $table->string('priority')->default('normal');
            $table->json('target_roles')->nullable();
            $table->json('target_staff_ids')->nullable();
            $table->json('attachments')->nullable();
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['category', 'published_at']);
            $table->index('priority');
            $table->index('is_pinned');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_announcements');
    }
};
