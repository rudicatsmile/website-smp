<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tahfidz_participants', function (Blueprint $table) {
            $table->foreignId('tahfidz_class_id')->nullable()->after('student_id')
                ->constrained('tahfidz_classes')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tahfidz_participants', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahfidz_class_id');
        });
    }
};
