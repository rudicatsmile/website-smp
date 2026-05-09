<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('counseling_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('code', 16)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reporter_name')->nullable();
            $table->string('reporter_contact')->nullable();
            $table->string('category', 32);
            $table->string('priority', 16)->default('medium');
            $table->string('status', 16)->default('new');
            $table->string('subject');
            $table->longText('body');
            $table->json('attachments')->nullable();
            $table->string('channel', 16)->default('portal'); // public | portal
            $table->boolean('is_anonymous')->default(false);
            $table->foreignId('assigned_to')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->dateTime('resolved_at')->nullable();
            $table->dateTime('last_activity_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'priority']);
            $table->index('assigned_to');
            $table->index('last_activity_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('counseling_tickets');
    }
};
