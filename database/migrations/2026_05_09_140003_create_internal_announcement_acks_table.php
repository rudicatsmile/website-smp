<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_announcement_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('internal_announcement_id')
                ->constrained('internal_announcements', 'id', 'ann_ack_announcement_fk')
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained('users', 'id', 'ann_ack_user_fk')
                ->cascadeOnDelete();
            $table->timestamp('acknowledged_at')->useCurrent();
            $table->timestamps();

            $table->unique(['internal_announcement_id', 'user_id'], 'announcement_user_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_announcement_acknowledgements');
    }
};
