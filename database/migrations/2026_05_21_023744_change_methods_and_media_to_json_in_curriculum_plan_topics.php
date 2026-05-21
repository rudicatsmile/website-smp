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
        DB::statement("UPDATE curriculum_plan_topics SET methods = NULL WHERE methods IS NOT NULL");
        DB::statement("UPDATE curriculum_plan_topics SET media = NULL WHERE media IS NOT NULL");
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->json('methods')->nullable()->change();
            $table->json('media')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE curriculum_plan_topics SET methods = NULL WHERE methods IS NOT NULL");
        DB::statement("UPDATE curriculum_plan_topics SET media = NULL WHERE media IS NOT NULL");
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->string('methods', 255)->nullable()->change();
            $table->string('media', 255)->nullable()->change();
        });
    }
};
