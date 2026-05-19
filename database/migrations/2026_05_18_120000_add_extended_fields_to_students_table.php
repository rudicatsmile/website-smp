<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Data Kependudukan
            $table->string('nik', 20)->nullable()->after('nisn');
            $table->string('religion', 32)->nullable()->after('nik');
            $table->string('kk_number', 20)->nullable()->after('religion');
            $table->string('birth_certificate_number')->nullable()->after('kk_number');
            $table->string('skhun')->nullable()->after('birth_certificate_number');

            // Alamat Lengkap
            $table->string('rt', 8)->nullable()->after('address');
            $table->string('rw', 8)->nullable()->after('rt');
            $table->string('dusun')->nullable()->after('rw');
            $table->string('kelurahan')->nullable()->after('dusun');
            $table->string('kecamatan')->nullable()->after('kelurahan');
            $table->string('postal_code', 10)->nullable()->after('kecamatan');
            $table->string('living_with')->nullable()->after('postal_code');
            $table->string('transportation')->nullable()->after('living_with');
            $table->string('phone', 32)->nullable()->after('transportation');

            // Data Ayah (extends existing parent_name)
            $table->smallInteger('father_birth_year')->nullable()->after('parent_name');
            $table->string('father_education')->nullable()->after('father_birth_year');
            $table->string('father_occupation')->nullable()->after('father_education');
            $table->string('father_income')->nullable()->after('father_occupation');
            $table->string('father_nik', 20)->nullable()->after('father_income');

            // Data Ibu (extends existing mother_name & mother_phone)
            $table->smallInteger('mother_birth_year')->nullable()->after('mother_name');
            $table->string('mother_education')->nullable()->after('mother_birth_year');
            $table->string('mother_occupation')->nullable()->after('mother_education');
            $table->string('mother_income')->nullable()->after('mother_occupation');
            $table->string('mother_nik', 20)->nullable()->after('mother_income');

            // Data Wali
            $table->string('guardian_name')->nullable()->after('mother_phone');
            $table->smallInteger('guardian_birth_year')->nullable()->after('guardian_name');
            $table->string('guardian_education')->nullable()->after('guardian_birth_year');
            $table->string('guardian_occupation')->nullable()->after('guardian_education');
            $table->string('guardian_income')->nullable()->after('guardian_occupation');
            $table->string('guardian_nik', 20)->nullable()->after('guardian_income');

            // Data Sekolah & Dokumen
            $table->string('previous_school')->nullable()->after('address');
            $table->tinyInteger('child_order')->nullable()->after('previous_school');
            $table->string('un_number')->nullable()->after('child_order');
            $table->string('certificate_number')->nullable()->after('un_number');

            // Bantuan Sosial
            $table->boolean('kps_recipient')->nullable()->after('certificate_number');
            $table->string('kps_number')->nullable()->after('kps_recipient');
            $table->boolean('kip_recipient')->nullable()->after('kps_number');
            $table->string('kip_number')->nullable()->after('kip_recipient');
            $table->string('kip_name')->nullable()->after('kip_number');
            $table->string('kks_number')->nullable()->after('kip_name');
            $table->boolean('pip_eligible')->nullable()->after('kks_number');
            $table->string('pip_reason')->nullable()->after('pip_eligible');

            // Data Fisik & Lokasi
            $table->smallInteger('weight')->nullable()->after('pip_reason');
            $table->smallInteger('height')->nullable()->after('weight');
            $table->smallInteger('head_circumference')->nullable()->after('height');
            $table->tinyInteger('siblings_count')->nullable()->after('head_circumference');
            $table->decimal('home_distance', 5, 2)->nullable()->after('siblings_count');
            $table->string('special_needs')->nullable()->after('home_distance');
            $table->decimal('latitude', 10, 7)->nullable()->after('special_needs');
            $table->decimal('longitude', 11, 7)->nullable()->after('latitude');

            // Rekening Bank
            $table->string('bank_name')->nullable()->after('longitude');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn([
                'nik', 'religion', 'kk_number', 'birth_certificate_number', 'skhun',
                'rt', 'rw', 'dusun', 'kelurahan', 'kecamatan', 'postal_code', 'living_with', 'transportation', 'phone',
                'father_birth_year', 'father_education', 'father_occupation', 'father_income', 'father_nik',
                'mother_birth_year', 'mother_education', 'mother_occupation', 'mother_income', 'mother_nik',
                'guardian_name', 'guardian_birth_year', 'guardian_education', 'guardian_occupation', 'guardian_income', 'guardian_nik',
                'previous_school', 'child_order', 'un_number', 'certificate_number',
                'kps_recipient', 'kps_number', 'kip_recipient', 'kip_number', 'kip_name', 'kks_number', 'pip_eligible', 'pip_reason',
                'weight', 'height', 'head_circumference', 'siblings_count', 'home_distance', 'special_needs', 'latitude', 'longitude',
                'bank_name', 'bank_account_number', 'bank_account_name',
            ]);
        });
    }
};
