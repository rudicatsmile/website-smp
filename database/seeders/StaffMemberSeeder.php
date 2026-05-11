<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\StaffCategory;
use App\Models\StaffMember;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StaffMemberSeeder extends Seeder
{
    public function run(): void
    {
        $categories = StaffCategory::all()->keyBy('slug');

        $staff = [
            [
                'name' => 'Dr. H. Ahmad Fauzi, M.Pd',
                'category_slug' => 'kepala-sekolah',
                'position' => 'Kepala Sekolah',
                'is_principal' => true,
                'gender' => 'L',
                'subjects' => [],
                'education' => [
                    ['degree' => 'S3', 'major' => 'Pendidikan', 'institution' => 'Universitas Negeri Jakarta', 'year' => '2010'],
                    ['degree' => 'S2', 'major' => 'Manajemen Pendidikan', 'institution' => 'Universitas Indonesia', 'year' => '2005'],
                ],
                'certifications' => [
                    ['name' => 'Sertifikasi Kepala Sekolah', 'issuer' => 'Kemdikbud', 'year' => '2012'],
                ],
                'experiences' => [
                    ['position' => 'Kepala Sekolah', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2015', 'end_year' => null],
                    ['position' => 'Wakasek Kurikulum', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2010', 'end_year' => '2015'],
                ],
                'bio' => 'Berdedikasi untuk memajukan pendidikan di lingkungan sekolah dengan pengalaman lebih dari 15 tahun di bidang manajemen pendidikan.',
                'quote' => 'Pendidikan adalah kunci masa depan bangsa.',
                'years_of_service' => 15,
            ],
            [
                'name' => 'Siti Aminah, S.Pd',
                'category_slug' => 'guru',
                'position' => 'Guru Matematika',
                'is_principal' => false,
                'gender' => 'P',
                'subjects' => ['Matematika', 'Statistika'],
                'education' => [
                    ['degree' => 'S1', 'major' => 'Pendidikan Matematika', 'institution' => 'Universitas Negeri Jakarta', 'year' => '2015'],
                ],
                'certifications' => [
                    ['name' => 'Sertifikasi Guru', 'issuer' => 'Kemdikbud', 'year' => '2018'],
                ],
                'experiences' => [
                    ['position' => 'Guru Matematika', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2015', 'end_year' => null],
                ],
                'bio' => 'Guru matematika yang bersemangat membantu siswa menguasai konsep matematika dengan pendekatan yang menyenangkan.',
                'quote' => 'Matematika adalah bahasa alam semesta.',
                'years_of_service' => 8,
            ],
            [
                'name' => 'Budi Santoso, S.Pd',
                'category_slug' => 'guru',
                'position' => 'Guru Bahasa Inggris',
                'is_principal' => false,
                'gender' => 'L',
                'subjects' => ['Bahasa Inggris', 'Bahasa Indonesia'],
                'education' => [
                    ['degree' => 'S1', 'major' => 'Pendidikan Bahasa Inggris', 'institution' => 'Universitas Negeri Jakarta', 'year' => '2016'],
                ],
                'certifications' => [
                    ['name' => 'TOEFL 600', 'issuer' => 'ETS', 'year' => '2017'],
                ],
                'experiences' => [
                    ['position' => 'Guru Bahasa Inggris', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2016', 'end_year' => null],
                ],
                'bio' => 'Guru bahasa Inggris dengan pengalaman mengajar menggunakan metode komunikatif untuk meningkatkan kemampuan berbicara siswa.',
                'quote' => 'Language is the key to understanding the world.',
                'years_of_service' => 7,
            ],
            [
                'name' => 'Dewi Lestari, S.Pd',
                'category_slug' => 'guru',
                'position' => 'Guru IPA',
                'is_principal' => false,
                'gender' => 'P',
                'subjects' => ['IPA', 'Biologi'],
                'education' => [
                    ['degree' => 'S1', 'major' => 'Pendidikan Biologi', 'institution' => 'Institut Pertanian Bogor', 'year' => '2014'],
                ],
                'certifications' => [
                    ['name' => 'Pelatihan Laboratorium IPA', 'issuer' => 'Dinas Pendidikan', 'year' => '2019'],
                ],
                'experiences' => [
                    ['position' => 'Guru IPA', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2014', 'end_year' => null],
                ],
                'bio' => 'Guru IPA yang berfokus pada eksperimen praktis untuk membangun pemahaman konsep sains siswa.',
                'quote' => 'Science is not just about facts, it is about curiosity.',
                'years_of_service' => 9,
            ],
            [
                'name' => 'Rahmat Hidayat, S.Kom',
                'category_slug' => 'guru',
                'position' => 'Guru TIK',
                'is_principal' => false,
                'gender' => 'L',
                'subjects' => ['TIK', 'Komputer'],
                'education' => [
                    ['degree' => 'S1', 'major' => 'Teknik Informatika', 'institution' => 'Institut Teknologi Bandung', 'year' => '2017'],
                ],
                'certifications' => [
                    ['name' => 'Google Certified Educator', 'issuer' => 'Google', 'year' => '2020'],
                ],
                'experiences' => [
                    ['position' => 'Guru TIK', 'organization' => 'SMP Al Wathoniyah 9', 'start_year' => '2017', 'end_year' => null],
                ],
                'bio' => 'Guru TIK yang membekali siswa dengan keterampilan digital untuk menghadapi tantangan era 4.0.',
                'quote' => 'Technology is best when it brings people together.',
                'years_of_service' => 6,
            ],
        ];

        foreach ($staff as $i => $data) {
            $category = $categories->get($data['category_slug']);
            StaffMember::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'staff_category_id' => $category?->id,
                    'position' => $data['position'],
                    'is_principal' => $data['is_principal'],
                    'gender' => $data['gender'],
                    'subjects' => $data['subjects'],
                    'education' => $data['education'],
                    'certifications' => $data['certifications'],
                    'experiences' => $data['experiences'],
                    'bio' => $data['bio'],
                    'quote' => $data['quote'],
                    'years_of_service' => $data['years_of_service'],
                    'order' => $i,
                    'is_active' => true,
                ]
            );
        }
    }
}
