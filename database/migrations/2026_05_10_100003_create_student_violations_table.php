<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('staff_member_id')->nullable()->constrained('staff_members')->nullOnDelete();
            $table->date('date');
            $table->string('category', 32); // kedisiplinan / atribut / akademik / lainnya
            $table->string('description');
            $table->unsignedSmallInteger('points')->default(0);
            $table->string('action_taken')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_violations');
    }
};
