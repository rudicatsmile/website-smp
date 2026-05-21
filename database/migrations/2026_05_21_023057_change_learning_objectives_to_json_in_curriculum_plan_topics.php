<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE curriculum_plan_topics SET learning_objectives = NULL WHERE learning_objectives IS NOT NULL");
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->json('learning_objectives')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->text('learning_objectives')->nullable()->change();
        });
    }
};
