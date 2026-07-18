### 1. Rencana Pembelajaran (Curriculum Plan)
Tabel utama dan relasi yang digunakan untuk mencatat Rencana Pembelajaran:
- `curriculum_plans` (Tabel utama yang menyimpan data rencana pembelajaran)
- `curriculum_plan_topics` (Tabel untuk menyimpan data topik/pertemuan dalam suatu rencana pembelajaran)

### 2. Sesi Mengajar (Lesson Session) & Mengajar Hari Ini
Menu "Mengajar Hari Ini" pada dasarnya adalah tampilan khusus (filter berdasarkan hari ini) dari data "Sesi Mengajar". Tabel-tabel yang digunakan adalah:
- `lesson_sessions` (Tabel utama untuk jadwal dan pelaksanaan sesi mengajar)
- `lesson_session_materials` (Tabel pivot untuk materi yang digunakan dalam sesi tersebut)
- `lesson_session_assignments` (Tabel pivot untuk tugas yang diberikan pada sesi tersebut)
- `lesson_session_cases` (Tabel untuk mencatat kasus/catatan khusus dalam suatu sesi)
- `session_assessments` (Tabel untuk mencatat rencana penilaian di sesi tersebut)
- `session_assessment_scores` (Tabel untuk mencatat nilai/skor dari penilaian sesi)

### 3. Master Data Pendukung (Pembelajaran)
Fitur-fitur di atas sangat bergantung pada data master pembelajaran berikut:
- `learning_objectives` (Tujuan Pembelajaran)
- `learning_models` (Model Pembelajaran)
- `learning_methods` (Metode Pembelajaran)
- `learning_media` (Media Pembelajaran)
- `kko_levels` (Level Kata Kerja Operasional)
- `assessment_types` (Jenis-jenis penilaian)
- `material_categories` (Kategori Mata Pelajaran/Materi)
- `school_classes` (Data Kelas yang diajar)
- `staff_members` (Data Guru yang mengajar)

---