# Bank Soal & Latihan (Quiz Online)

Bangun fitur kuis interaktif end-to-end: bank soal per mapel di Filament untuk guru/admin, kuis hybrid (per kelas atau publik) dengan timer, multi-attempt, acak soal/opsi, auto-scoring (PG/Multi) + grading manual (Essay), pembahasan, dan leaderboard kelas di portal siswa.

## 1. Cakupan
- **Tipe soal**: Pilihan Ganda (PG, 1 benar), Multi-Answer (≥1 benar), Essay (manual grading).
- **Akses**: Hybrid — guru atur per kuis: `assigned` (target `school_classes`) atau `public` (semua siswa).
- **Batasan**: durasi (menit), max attempt, jendela aktif (`opens_at`–`closes_at`), acak soal & opsi (toggle), tampilkan pembahasan setelah submit (toggle).
- **Bank soal global** per mapel (`material_categories`) — sumber soal yang dipakai banyak kuis (snapshot ke `quiz_questions` saat kuis dipublish, agar nilai historis stabil).
- **Leaderboard** per kuis berdasarkan attempt terbaik tiap siswa.

## 2. Database Migrations
1. `question_banks` — `id, material_category_id (subject), staff_member_id (author), title, description, is_active, timestamps`.
2. `bank_questions` — `id, question_bank_id, type (enum: mcq|multi|essay), body (text), explanation (nullable), score (default 1), order, timestamps`.
3. `bank_question_options` — `id, bank_question_id, label (text), is_correct (bool), order`.
4. `quizzes` — `id, material_category_id, school_class_id (nullable for public), staff_member_id, title, slug, description, scope (enum: assigned|public), duration_minutes (nullable), max_attempts (default 1), shuffle_questions, shuffle_options, show_explanation, show_score_immediately, opens_at, closes_at, total_score (cached), is_published, published_at, timestamps`.
5. `quiz_questions` — snapshot soal tiap kuis: `id, quiz_id, bank_question_id (nullable), type, body, explanation, score, order`.
6. `quiz_question_options` — snapshot opsi: `id, quiz_question_id, label, is_correct, order`.
7. `quiz_attempts` — `id, quiz_id, student_id, attempt_no, started_at, submitted_at, score (nullable hingga essay graded), max_score, is_graded, graded_by, graded_at, timestamps, unique(quiz_id, student_id, attempt_no)`.
8. `quiz_answers` — `id, quiz_attempt_id, quiz_question_id, selected_option_ids (json), essay_text (nullable), is_correct (nullable), score_awarded (nullable), feedback (nullable)`.

## 3. Models & Relations
- `QuestionBank` hasMany `BankQuestion`; belongsTo `MaterialCategory` (subject), `StaffMember` (author).
- `BankQuestion` hasMany `BankQuestionOption`; belongsTo `QuestionBank`.
- `Quiz` hasMany `QuizQuestion`, `QuizAttempt`; belongsTo `MaterialCategory`, `SchoolClass` (nullable), `StaffMember`. Scopes: `published()`, `active()` (between opens_at/closes_at), `forStudent($student)` (public OR class match).
- `QuizQuestion` hasMany `QuizQuestionOption`; belongsTo `Quiz`.
- `QuizAttempt` hasMany `QuizAnswer`; belongsTo `Quiz`, `Student`. Accessors: `time_remaining`, `is_in_progress`, `is_finished`, `needs_grading` (ada essay belum dinilai).
- `QuizAnswer` belongsTo `QuizAttempt`, `QuizQuestion`. Cast `selected_option_ids` array.

## 4. Filament Resources (Akademik group)
- **`QuestionBankResource`** — CRUD bank soal. Form: subject, title, description, status. Relation manager: `BankQuestionsRelationManager` dengan repeater opsi (+is_correct), tipe soal, body (RichEditor), explanation, score. Akses: `super_admin`, `admin`, `teacher` (scope ke `staff_member_id` sendiri).
- **`QuizResource`** — CRUD kuis. Form sections:
  - **Info**: title, subject, scope (assigned/public), school_class (visible jika assigned), description.
  - **Settings**: duration_minutes, max_attempts, shuffle_questions, shuffle_options, show_explanation, show_score_immediately, opens_at, closes_at.
  - **Soal**: dua mode (mutually exclusive)
    - *Pilih dari bank*: pilih `QuestionBank`, lalu select N soal (atau "ambil random N").
    - *Tulis manual*: repeater `quiz_questions` + opsi (mirror bank).
  - **Publish**: toggle `is_published` → saat publish, snapshot soal + opsi ke `quiz_questions`/`quiz_question_options`, hitung `total_score`.
  - Action: **Duplicate**, **Re-snapshot** (hanya jika belum ada attempt).
  - Relation manager: `AttemptsRelationManager` (lihat semua attempt; action grade essay).
- Akses: super_admin, admin, teacher (own only).

## 5. Portal Siswa (Livewire) — `/portal/latihan`
- **`/portal/latihan`** — `QuizIndex`: daftar kuis aktif untuk siswa (public + class-assigned), filter status (`tersedia`, `sedang berjalan`, `selesai`), badge skor terbaik, sisa attempt, jendela waktu.
- **`/portal/latihan/{slug}`** — `QuizShow`: ringkasan kuis (durasi, jumlah soal, total skor, attempt terpakai, opens/closes), tombol **Mulai** (membuat `QuizAttempt` baru jika quota & jendela ok). Daftar attempt sebelumnya + skor.
- **`/portal/latihan/{slug}/kerjakan/{attempt}`** — `QuizPlay`:
  - Soal acak (jika diaktifkan) dari snapshot.
  - Auto-save jawaban via Livewire (debounce) ke `quiz_answers`.
  - Timer client-side (Alpine) berdasarkan `started_at + duration_minutes`; auto-submit saat 0.
  - Submit manual → hitung skor PG/Multi, set `is_graded=false` jika ada essay; redirect ke result.
- **`/portal/latihan/{slug}/hasil/{attempt}`** — `QuizResult`:
  - Skor (atau "Menunggu Penilaian Essay"), per-soal benar/salah, pembahasan (jika diizinkan).
- **`/portal/latihan/{slug}/leaderboard`** — `QuizLeaderboard`: top 10 siswa kelas (nilai terbaik), highlight posisi siswa saat ini.

Tambahkan link **Latihan** di header portal (`portal.blade.php`) dengan ikon clipboard-check, route `portal.quizzes.index`.

## 6. Routes & Middleware
`routes/web.php` di group `portal` (auth):
```
Route::get('/latihan', QuizIndex::class)->name('quizzes.index');
Route::get('/latihan/{slug}', QuizShow::class)->name('quizzes.show');
Route::get('/latihan/{slug}/kerjakan/{attempt}', QuizPlay::class)->name('quizzes.play');
Route::get('/latihan/{slug}/hasil/{attempt}', QuizResult::class)->name('quizzes.result');
Route::get('/latihan/{slug}/leaderboard', QuizLeaderboard::class)->name('quizzes.leaderboard');
```

## 7. Skoring Logic
- **MCQ**: benar = `selected_option_ids` panjang 1 dan id ∈ correct. Skor: full / 0.
- **Multi**: full hanya jika set selected = set correct (otherwise 0). Opsional partial credit di v2 (skip).
- **Essay**: `is_correct=null`, `score_awarded=null` sampai guru grade via Filament.
- `QuizAttempt.score` = sum `score_awarded`. `is_graded=true` saat semua answer punya `score_awarded` ≠ null.
- `QuizAttempt.max_score` = sum `quiz_questions.score`.

## 8. Seeder
`QuizContentSeeder`:
- 1 `QuestionBank` "Matematika UAS" dengan 10 soal (campuran PG/Multi/Essay).
- 2 `Quiz`: (a) "Latihan UAS Matematika 7" assigned ke kelas 7A, durasi 30 mnt, 1 attempt; (b) "Latihan Cepat Bahasa Indonesia" public, 3 attempts, 15 mnt.
- 3 dummy `QuizAttempt` untuk demo student kelas 7A (1 selesai dinilai, 1 berjalan, 1 essay menunggu grade).

Daftarkan di `DatabaseSeeder` setelah `PortalContentSeeder`.

## 9. UI/UX Notes
- Filter aktif gunakan **inline style** untuk warna teks (sesuai pola fix terbaru di `assignment-index`) agar pasti terbaca.
- Halaman pengerjaan: layout fokus (sidebar daftar nomor soal + status terjawab, area soal di tengah, timer sticky atas).
- Konfirmasi modal saat submit / saat keluar di tengah pengerjaan.

## 10. Verification
1. `php artisan migrate`.
2. `php artisan db:seed --class=QuizContentSeeder`.
3. Login admin → cek menu **Bank Soal** & **Kuis** di group Akademik; buat bank soal & kuis manual; publish.
4. Login guru → hanya melihat bank/kuis miliknya; grade attempt essay.
5. Login siswa demo (`siswa@smpalwathoniyah9.sch.id`) → `/portal/latihan`, kerjakan kuis, lihat hasil + leaderboard.
6. Verifikasi auto-submit saat timer habis & batasan max_attempts.

## 11. Out of Scope (v1)
- Partial credit multi-answer.
- Soal dengan gambar di body (opsi: pakai RichEditor — bisa, tapi tidak ada upload helper khusus).
- Anti-cheat (tab switch detection) — bisa di v2.
- Export hasil ke Excel/PDF.
