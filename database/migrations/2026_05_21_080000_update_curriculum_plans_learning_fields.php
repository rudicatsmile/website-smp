<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('curriculum_plans', function (Blueprint $table) {
            $table->dropColumn(['description', 'default_methods', 'default_media']);
        });

        Schema::table('curriculum_plans', function (Blueprint $table) {
            $table->string('time_allocation', 50)->nullable()->after('title');
            $table->json('learning_objective_ids')->nullable()->after('time_allocation');
            $table->json('learning_model_ids')->nullable()->after('learning_objective_ids');
            $table->json('default_methods')->nullable()->after('learning_model_ids');
            $table->json('default_media')->nullable()->after('default_methods');
        });
    }

    public function down(): void
    {
        Schema::table('curriculum_plans', function (Blueprint $table) {
            $table->dropColumn(['time_allocation', 'learning_objective_ids', 'learning_model_ids', 'default_methods', 'default_media']);
        });

        Schema::table('curriculum_plans', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
            $table->string('default_methods', 255)->nullable();
            $table->string('default_media', 255)->nullable();
        });
    }
};
