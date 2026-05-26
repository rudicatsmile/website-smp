<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->json('learning_paths')->nullable()->after('learning_objectives');
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_plan_topics', function (Blueprint $table) {
            $table->dropColumn('learning_paths');
        });
    }
};
