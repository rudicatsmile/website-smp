<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BankQuestion;
use App\Models\MaterialCategory;
use App\Models\QuestionBank;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;
use App\Models\QuizQuestion;
use App\Models\SchoolClass;
use App\Models\StaffMember;
use App\Models\Student;
use Illuminate\Database\Seeder;

class QuizContentSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = StaffMember::active()->first();
        $subject = MaterialCategory::first();
        $kelas7A = SchoolClass::where('name', '7A')->first();

        // 1) Bank soal
        $bank = QuestionBank::firstOrCreate(
            ['title' => 'Bank Soal Matematika UAS'],
            [
                'material_category_id' => $subject?->id,
                'staff_member_id' => $teacher?->id,
                'description' => 'Kumpulan soal latihan matematika untuk UAS.',
                'is_active' => true,
            ],
        );

        if ($bank->questions()->count() === 0) {
            $defs = [
                ['type' => 'mcq', 'body' => 'Hasil dari 12 + 8 × 2 adalah?', 'score' => 1, 'opts' => [
                    ['label' => '40', 'is_correct' => false],
                    ['label' => '28', 'is_correct' => true],
                    ['label' => '20', 'is_correct' => false],
                    ['label' => '32', 'is_correct' => false],
                ], 'expl' => 'Operasi perkalian didahulukan: 8×2=16, lalu 12+16=28.'],
                ['type' => 'mcq', 'body' => 'Bilangan prima terkecil adalah?', 'score' => 1, 'opts' => [
                    ['label' => '0', 'is_correct' => false],
                    ['label' => '1', 'is_correct' => false],
                    ['label' => '2', 'is_correct' => true],
                    ['label' => '3', 'is_correct' => false],
                ], 'expl' => '2 adalah bilangan prima terkecil dan satu-satunya prima genap.'],
                ['type' => 'mcq', 'body' => 'Luas persegi dengan sisi 7 cm adalah?', 'score' => 2, 'opts' => [
                    ['label' => '14 cm²', 'is_correct' => false],
                    ['label' => '28 cm²', 'is_correct' => false],
                    ['label' => '49 cm²', 'is_correct' => true],
                    ['label' => '21 cm²', 'is_correct' => false],
                ], 'expl' => 'Luas = sisi × sisi = 7 × 7 = 49.'],
                ['type' => 'multi', 'body' => 'Manakah yang termasuk bilangan genap? (pilih semua)', 'score' => 2, 'opts' => [
                    ['label' => '2', 'is_correct' => true],
                    ['label' => '5', 'is_correct' => false],
                    ['label' => '10', 'is_correct' => true],
                    ['label' => '7', 'is_correct' => false],
                    ['label' => '12', 'is_correct' => true],
                ], 'expl' => 'Bilangan genap habis dibagi 2.'],
                ['type' => 'mcq', 'body' => 'KPK dari 4 dan 6 adalah?', 'score' => 1, 'opts' => [
                    ['label' => '8', 'is_correct' => false],
                    ['label' => '10', 'is_correct' => false],
                    ['label' => '12', 'is_correct' => true],
                    ['label' => '24', 'is_correct' => false],
                ], 'expl' => 'KPK(4,6) = 12.'],
                ['type' => 'mcq', 'body' => 'Berapakah ¾ dari 80?', 'score' => 1, 'opts' => [
                    ['label' => '40', 'is_correct' => false],
                    ['label' => '50', 'is_correct' => false],
                    ['label' => '60', 'is_correct' => true],
                    ['label' => '70', 'is_correct' => false],
                ], 'expl' => '¾ × 80 = 60.'],
                ['type' => 'mcq', 'body' => 'Hasil dari (-5) + 8 adalah?', 'score' => 1, 'opts' => [
                    ['label' => '-3', 'is_correct' => false],
                    ['label' => '3', 'is_correct' => true],
                    ['label' => '13', 'is_correct' => false],
                    ['label' => '-13', 'is_correct' => false],
                ], 'expl' => '-5 + 8 = 3.'],
                ['type' => 'multi', 'body' => 'Manakah faktor dari 12? (pilih semua)', 'score' => 2, 'opts' => [
                    ['label' => '1', 'is_correct' => true],
                    ['label' => '2', 'is_correct' => true],
                    ['label' => '5', 'is_correct' => false],
                    ['label' => '6', 'is_correct' => true],
                    ['label' => '8', 'is_correct' => false],
                ], 'expl' => 'Faktor 12: 1, 2, 3, 4, 6, 12.'],
                ['type' => 'essay', 'body' => 'Jelaskan dengan kata-katamu sendiri apa itu bilangan prima dan berikan 3 contoh!', 'score' => 5, 'opts' => [], 'expl' => 'Bilangan prima adalah bilangan asli >1 yang hanya habis dibagi 1 dan dirinya sendiri. Contoh: 2, 3, 5, 7, 11.'],
                ['type' => 'essay', 'body' => 'Hitung luas dan keliling persegi panjang dengan panjang 12 cm dan lebar 5 cm. Tuliskan langkah pengerjaan!', 'score' => 5, 'opts' => [], 'expl' => 'Luas = p×l = 60 cm². Keliling = 2(p+l) = 34 cm.'],
            ];

            foreach ($defs as $i => $def) {
                $q = BankQuestion::create([
                    'question_bank_id' => $bank->id,
                    'type' => $def['type'],
                    'body' => $def['body'],
                    'explanation' => $def['expl'],
                    'score' => $def['score'],
                    'order' => $i + 1,
                ]);
                foreach ($def['opts'] as $j => $opt) {
                    $q->options()->create([
                        'label' => $opt['label'],
                        'is_correct' => $opt['is_correct'],
                        'order' => $j + 1,
                    ]);
                }
            }
        }

        // 2) Quiz: Latihan UAS Matematika 7 (assigned ke 7A)
        $quiz1 = Quiz::firstOrCreate(
            ['slug' => 'latihan-uas-matematika-7'],
            [
                'material_category_id' => $subject?->id,
                'school_class_id' => $kelas7A?->id,
                'staff_member_id' => $teacher?->id,
                'title' => 'Latihan UAS Matematika Kelas 7',
                'description' => '<p>Kerjakan dengan teliti. Durasi 30 menit, 1 kali kesempatan.</p>',
                'scope' => 'assigned',
                'duration_minutes' => 30,
                'max_attempts' => 1,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_explanation' => true,
                'show_score_immediately' => true,
                'opens_at' => now()->subDays(1),
                'closes_at' => now()->addDays(14),
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
        );

        $this->snapshotFromBank($quiz1, $bank);

        // 3) Quiz: Latihan Cepat (public)
        $quiz2 = Quiz::firstOrCreate(
            ['slug' => 'latihan-cepat-bahasa-indonesia'],
            [
                'material_category_id' => $subject?->id,
                'school_class_id' => null,
                'staff_member_id' => $teacher?->id,
                'title' => 'Latihan Cepat Bahasa Indonesia',
                'description' => '<p>Latihan terbuka untuk semua siswa. 3 kali kesempatan, 15 menit per attempt.</p>',
                'scope' => 'public',
                'duration_minutes' => 15,
                'max_attempts' => 3,
                'shuffle_questions' => true,
                'shuffle_options' => true,
                'show_explanation' => true,
                'show_score_immediately' => true,
                'opens_at' => now()->subDays(1),
                'closes_at' => now()->addDays(30),
                'is_published' => true,
                'published_at' => now()->subDays(1),
            ],
        );

        // Public quiz: pakai 5 soal pertama dari bank
        $this->snapshotFromBank($quiz2, $bank, 5);

        // 4) Demo attempts untuk siswa demo
        $demoStudent = Student::where('nis', '0001')->first();
        if ($demoStudent && $quiz1->questions()->count() > 0 && $quiz1->attempts()->where('student_id', $demoStudent->id)->count() === 0) {
            // Attempt selesai dinilai (semua benar pada PG, essay belum karena akan butuh manual)
            // Untuk demo, kita buat 1 attempt yang submitted dan auto-graded (semua MCQ benar, essay 0)
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz1->id,
                'student_id' => $demoStudent->id,
                'attempt_no' => 1,
                'started_at' => now()->subHours(2),
                'submitted_at' => now()->subHours(1)->subMinutes(45),
                'max_score' => $quiz1->total_score,
            ]);

            $totalScore = 0;
            $hasEssay = false;
            foreach ($quiz1->questions()->with('options')->get() as $q) {
                if ($q->type === 'essay') {
                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'quiz_question_id' => $q->id,
                        'essay_text' => 'Bilangan prima adalah bilangan yang hanya bisa dibagi 1 dan dirinya sendiri. Contohnya 2, 3, dan 5.',
                    ]);
                    $hasEssay = true;
                } else {
                    $correctIds = $q->options->where('is_correct', true)->pluck('id')->all();
                    QuizAnswer::create([
                        'quiz_attempt_id' => $attempt->id,
                        'quiz_question_id' => $q->id,
                        'selected_option_ids' => $correctIds,
                        'is_correct' => true,
                        'score_awarded' => $q->score,
                    ]);
                    $totalScore += $q->score;
                }
            }
            $attempt->update([
                'score' => $totalScore,
                'is_graded' => ! $hasEssay,
            ]);
        }
    }

    private function snapshotFromBank(Quiz $quiz, QuestionBank $bank, ?int $limit = null): void
    {
        if ($quiz->questions()->count() > 0) {
            return; // already has snapshot
        }
        $query = $bank->questions()->with('options')->orderBy('order');
        if ($limit) $query->limit($limit);
        $questions = $query->get();

        $order = 0;
        foreach ($questions as $bq) {
            $order++;
            $qq = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'bank_question_id' => $bq->id,
                'type' => $bq->type,
                'body' => $bq->body,
                'explanation' => $bq->explanation,
                'score' => $bq->score,
                'order' => $order,
            ]);
            foreach ($bq->options as $opt) {
                $qq->options()->create([
                    'label' => $opt->label,
                    'is_correct' => $opt->is_correct,
                    'order' => $opt->order,
                ]);
            }
        }

        $quiz->update(['total_score' => $quiz->questions()->sum('score')]);
    }
}
