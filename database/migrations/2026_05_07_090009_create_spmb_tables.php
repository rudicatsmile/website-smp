<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_periods', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedInteger('quota')->default(0);
            $table->unsignedBigInteger('fee')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false)->index();
            $table->timestamps();
        });

        Schema::create('spmb_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spmb_period_id')->constrained('spmb_periods')->cascadeOnDelete();
            $table->string('registration_number')->unique();

            // Data diri
            $table->string('full_name');
            $table->string('nick_name')->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('nik', 32)->nullable();
            $table->string('nisn', 32)->nullable();
            $table->string('religion')->nullable();
            $table->text('address');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Orang tua
            $table->string('father_name')->nullable();
            $table->string('father_job')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_job')->nullable();
            $table->string('mother_phone')->nullable();
            $table->string('guardian_name')->nullable();

            // Asal sekolah
            $table->string('previous_school')->nullable();
            $table->string('graduation_year')->nullable();
            $table->string('npsn')->nullable();

            $table->enum('status', ['pending', 'verifying', 'accepted', 'rejected', 'waiting_list'])->default('pending')->index();
            $table->text('admin_note')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('spmb_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spmb_registration_id')->constrained('spmb_registrations')->cascadeOnDelete();
            $table->enum('type', ['kk', 'akta', 'foto', 'ijazah', 'raport', 'lainnya']);
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_documents');
        Schema::dropIfExists('spmb_registrations');
        Schema::dropIfExists('spmb_periods');
    }
};
