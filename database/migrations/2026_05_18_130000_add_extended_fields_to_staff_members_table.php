<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            // Kepegawaian
            $table->string('employment_status')->nullable()->after('years_of_service');
            $table->string('ptk_type')->nullable()->after('employment_status');
            $table->string('sk_cpns')->nullable()->after('ptk_type');
            $table->date('sk_cpns_date')->nullable()->after('sk_cpns');
            $table->string('sk_appointment')->nullable()->after('sk_cpns_date');
            $table->string('appointing_agency')->nullable()->after('sk_appointment');
            $table->string('rank_grade')->nullable()->after('appointing_agency');
            $table->string('salary_source')->nullable()->after('rank_grade');
            $table->date('civil_servant_start_date')->nullable()->after('salary_source');
            $table->string('nuks')->nullable()->after('civil_servant_start_date');

            // Alamat
            $table->text('address')->nullable()->after('nuks');
            $table->string('rt', 8)->nullable()->after('address');
            $table->string('rw', 8)->nullable()->after('rt');
            $table->string('dusun')->nullable()->after('rw');
            $table->string('kelurahan')->nullable()->after('dusun');
            $table->string('kecamatan')->nullable()->after('kelurahan');
            $table->string('postal_code', 10)->nullable()->after('kecamatan');
            $table->string('phone_home', 50)->nullable()->after('postal_code');

            // Data Pribadi
            $table->string('religion', 32)->nullable()->after('phone_home');
            $table->string('nik', 20)->nullable()->after('religion');
            $table->string('kk_number', 20)->nullable()->after('nik');
            $table->string('mother_name')->nullable()->after('kk_number');
            $table->string('marital_status', 32)->nullable()->after('mother_name');
            $table->string('spouse_name')->nullable()->after('marital_status');
            $table->string('spouse_nip', 50)->nullable()->after('spouse_name');
            $table->string('spouse_occupation')->nullable()->after('spouse_nip');
            $table->string('nationality', 32)->nullable()->after('spouse_occupation');
            $table->string('npwp', 32)->nullable()->after('nationality');
            $table->string('taxpayer_name')->nullable()->after('npwp');

            // Dokumen Kepegawaian
            $table->string('karpeg', 32)->nullable()->after('taxpayer_name');
            $table->string('karis_karsu', 32)->nullable()->after('karpeg');

            // Kompetensi Khusus
            $table->boolean('has_principal_license')->nullable()->after('karis_karsu');
            $table->boolean('has_supervision_training')->nullable()->after('has_principal_license');
            $table->boolean('braille_skill')->nullable()->after('has_supervision_training');
            $table->boolean('sign_language_skill')->nullable()->after('braille_skill');

            // Bank
            $table->string('bank_name')->nullable()->after('sign_language_skill');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');

            // GPS
            $table->decimal('latitude', 10, 7)->nullable()->after('bank_account_name');
            $table->decimal('longitude', 11, 7)->nullable()->after('latitude');
        });
    }

    public function down(): void
    {
        Schema::table('staff_members', function (Blueprint $table) {
            $table->dropColumn([
                'employment_status', 'ptk_type', 'sk_cpns', 'sk_cpns_date', 'sk_appointment',
                'appointing_agency', 'rank_grade', 'salary_source', 'civil_servant_start_date', 'nuks',
                'address', 'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'postal_code', 'phone_home',
                'religion', 'nik', 'kk_number', 'mother_name', 'marital_status',
                'spouse_name', 'spouse_nip', 'spouse_occupation', 'nationality', 'npwp', 'taxpayer_name',
                'karpeg', 'karis_karsu',
                'has_principal_license', 'has_supervision_training', 'braille_skill', 'sign_language_skill',
                'bank_name', 'bank_account_number', 'bank_account_name',
                'latitude', 'longitude',
            ]);
        });
    }
};
