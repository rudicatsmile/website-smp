<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counseling_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counseling_ticket_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type', 16); // student | counselor | anonymous
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('body');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false);
            $table->timestamps();

            $table->index(['counseling_ticket_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_messages');
    }
};
