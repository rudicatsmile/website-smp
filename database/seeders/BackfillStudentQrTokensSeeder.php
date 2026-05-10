<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;

class BackfillStudentQrTokensSeeder extends Seeder
{
    public function run(): void
    {
        Student::whereNull('qr_token')->orderBy('id')->get()
            ->each(fn (Student $s) => $s->generateQrToken());
    }
}
