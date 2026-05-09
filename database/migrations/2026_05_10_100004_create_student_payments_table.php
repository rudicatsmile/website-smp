<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32); // spp / seragam / kegiatan / lainnya
            $table->string('period', 48); // Jan 2026 / Seragam Kelas 7
            $table->unsignedInteger('amount');
            $table->date('due_date')->nullable();
            $table->string('status', 16)->default('unpaid'); // unpaid / paid / overdue
            $table->timestamp('paid_at')->nullable();
            $table->unsignedInteger('paid_amount')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_payments');
    }
};
