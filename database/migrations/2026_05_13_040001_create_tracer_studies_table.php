<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracer_studies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->smallInteger('graduation_year');
            $table->string('current_status'); // working | studying | entrepreneur | both | unemployed | other
            $table->string('company_or_institution')->nullable();
            $table->string('position')->nullable();
            $table->string('city')->nullable();
            $table->string('income_range')->nullable();
            // <2jt | 2-5jt | 5-10jt | 10-20jt | >20jt | prefer_not_to_say
            $table->unsignedTinyInteger('school_relevance')->nullable(); // 1-5
            $table->unsignedTinyInteger('school_quality')->nullable();   // 1-5
            $table->text('suggestions')->nullable();
            $table->boolean('allow_publish')->default(false);
            $table->boolean('is_processed')->default(false);
            $table->timestamp('processed_at')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('graduation_year');
            $table->index('is_processed');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracer_studies');
    }
};
