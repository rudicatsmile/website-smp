<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parent_note_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_note_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type', 16);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->text('body');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['parent_note_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_note_messages');
    }
};
