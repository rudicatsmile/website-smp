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
        DB::statement("UPDATE lesson_sessions SET learning_objectives = NULL WHERE learning_objectives IS NOT NULL");
        DB::statement("UPDATE lesson_sessions SET methods = NULL WHERE methods IS NOT NULL");
        DB::statement("UPDATE lesson_sessions SET media = NULL WHERE media IS NOT NULL");
        Schema::table('lesson_sessions', function (Blueprint $table) {
            $table->json('learning_objectives')->nullable()->change();
            $table->json('methods')->nullable()->change();
            $table->json('media')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE lesson_sessions SET learning_objectives = NULL WHERE learning_objectives IS NOT NULL");
        DB::statement("UPDATE lesson_sessions SET methods = NULL WHERE methods IS NOT NULL");
        DB::statement("UPDATE lesson_sessions SET media = NULL WHERE media IS NOT NULL");
        Schema::table('lesson_sessions', function (Blueprint $table) {
            $table->text('learning_objectives')->nullable()->change();
            $table->string('methods', 255)->nullable()->change();
            $table->string('media', 255)->nullable()->change();
        });
    }
};
