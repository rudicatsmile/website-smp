# Alur Kerja: Manajemen Materi Pelajaran Harian

## Ringkasan

Fitur ini mengelola perencanaan kurikulum per kelas dan pelaksanaan harian oleh guru. Kepala Kurikulum / Admin menyusun template kurikulum (daftar topik per pekan), lalu bulk-apply ke rentang tanggal menjadi sesi mengajar konkret. Guru mengeksekusi sesi harian melalui halaman **Mengajar Hari Ini**.

---

## Struktur Database

| Tabel | Fungsi |
|---|---|
| `curriculum_plans` | Template kurikulum per kelas, mapel, guru, tahun ajaran, semester |
| `curriculum_plan_topics` | Daftar topik per pekan dalam satu rencana |
| `lesson_sessions` | Instance sesi mengajar per tanggal aktual + data realisasi |
| `lesson_session_materials` | Pivot ke ClassMaterial (materi ajar per sesi) |
| `lesson_session_assignments` | Pivot ke ClassAssignment (tugas per sesi) |

---

## Alur Kerja

### Tahap 1 — Perencanaan Kurikulum

**Aktor**: Kepala Kurikulum / Admin

1. Buka **Materi Pelajaran › Kurikulum** (`/admin/curriculum-plans`)
2. Klik **New Rencana Kurikulum**
3. Isi form:
   - Kelas (`school_class_id`)
   - Mata Pelajaran (`material_category_id`)
   - Guru Pengajar (`staff_member_id`)
   - Tahun Ajaran & Semester
   - Metode default, media, rencana asesmen (opsional)
4. Simpan → buka halaman edit
5. Tab **Topics** → tambahkan topik per pekan:
   - Pekan 1: "Bilangan Bulat" (tujuan, metode, asesmen)
   - Pekan 2: "Operasi Hitung Campuran"
   - Pekan 3: "FPB dan KPK"
   - ... dst
6. Kembali ke halaman edit → klik action **🗓 Apply ke Tanggal**
7. Isi dialog:
   - Tanggal mulai & selesai
   - Hari mengajar (centang Senin, Rabu, Jumat, dll)
   - Jam mulai & selesai (contoh: 07:00 – 08:30)
   - Periode / jam ke- (opsional)
   - Skip Minggu & hari libur (default: ya)
   - Publish langsung (opsional)
8. Submit → `CurriculumPlanService.applyToDateRange()`:
   - Mengelompokkan tanggal per pekan ISO
   - Mendistribusikan topik secara round-robin (jika lebih banyak pekan daripada topik, topik akan berulang)
   - Insert massal ke tabel `lesson_sessions`
   - Status: `draft` atau `published` (tergantung opsi)

### Tahap 2 — Pelaksanaan Harian

**Aktor**: Guru

1. Buka **Materi Pelajaran › Mengajar Hari Ini** (`/admin/teaching-today`)
2. Halaman menampilkan kartu sesi untuk **tanggal hari ini** milik guru yang login
3. Setiap kartu menampilkan:
   - Jam (time_range) + status badge
   - Topik, mapel, kelas
   - Tujuan pembelajaran
   - Materi terkait (link file)
4. **Sesi "published"** → klik **Mulai**:
   - `LessonExecutionService.start()` dipanggil
   - Status berubah: `published` → `ongoing`
   - `actual_start_at` dicatat
   - Validasi: guru harus sesuai jadwal, status harus `published` atau `ongoing`
5. **Sesi "ongoing"** → klik **Selesai**:
   - Form realisasi muncul (inline):
     - Pencapaian (%) — 0–100
     - Catatan Kegiatan
     - Tugas / PR
     - Kendala
   - Submit → `LessonExecutionService.complete()` dipanggil
   - Status berubah: `ongoing` → `completed`
   - `actual_end_at`, `completed_at` dicatat
6. **Kapan saja** → klik **Batal**:
   - `LessonExecutionService.cancel()` dipanggil
   - Status berubah → `cancelled`
   - Alasan pembatalan wajib diisi
   - Sesi `completed` tidak bisa dibatalkan

### Tahap 3 — Monitoring & Notifikasi

**Aktor**: Admin / Sistem

1. **Materi Pelajaran › Sesi Mengajar** (`/admin/lesson-sessions`):
   - Tabel semua sesi dengan filter: kelas, mapel, guru, tanggal, status
   - Bulk action: Publish, Cancel
   - Relation Managers: Materials, Assignments

2. **Notifikasi Otomatis** (scheduler tiap 5 menit):
   - Command: `php artisan lessons:dispatch-reminders`
   - `LessonReminderNotifier.dispatchUpcoming()`:
     - Mencari sesi `published` hari ini
     - Yang `start_time` ≈ 30 menit dari sekarang (window ±5 menit)
     - Kirim WhatsApp + Email ke guru
   - Konfigurasi `.env`:
     ```
     NOTIF_LESSON_REMINDER=true
     NOTIF_LESSON_MINUTES_BEFORE=30
     ```

3. **Dashboard Widget**: `LessonProgressWidget`
   - Doughnut chart penyelesaian materi bulan ini

---

## Status Sesi

| Status | Label | Warna | Arti |
|---|---|---|---|
| `draft` | Draft | Slate | Belum dipublish |
| `published` | Published | Blue | Siap diajarkan, belum dimulai |
| `ongoing` | Sedang Berlangsung | Orange | Guru sedang mengajar |
| `completed` | Selesai | Green | Selesai + realisasi tercatat |
| `cancelled` | Dibatalkan | Red | Dibatalkan dengan alasan |

Transisi yang valid:
```
draft → published → ongoing → completed
                   → cancelled
         ongoing → cancelled
```

---

## Manajemen Role & Akses

### Role yang relevan

| Role | Deskripsi |
|---|---|
| `super_admin` | Akses penuh ke semua fitur |
| `admin` | Akses penuh ke semua fitur |
| `teacher` | Akses terbatas ke sesi milik sendiri |

### Matriks Akses

| Operasi | super_admin | admin | teacher |
|---|---|---|---|
| Buat rencana kurikulum | ✅ | ✅ | ❌ |
| Tambah topik ke rencana | ✅ | ✅ | ❌ |
| Apply ke tanggal (bulk generate sesi) | ✅ | ✅ | ❌ |
| Lihat daftar kurikulum | Semua | Semua | Milik sendiri |
| Lihat daftar sesi mengajar | Semua | Semua | Milik sendiri |
| Publish / Cancel sesi (bulk) | ✅ | ✅ | ❌ |
| Mulai mengajar (start) | ✅ | ✅ | ✅ (milik sendiri) |
| Selesai & catat realisasi | ✅ | ✅ | ✅ (milik sendiri) |
| Batalkan sesi | ✅ | ✅ | ✅ (milik sendiri) |
| Terima reminder WA/Email | — | — | ✅ (otomatis) |

### Mekanisme Pembatasan

1. **Gate panel** (`User::canAccessPanel`): hanya role `super_admin`, `admin`, `teacher`, dll yang bisa masuk Filament
2. **Gate resource** (`canAccess()`): `CurriculumPlanResource`, `LessonSessionResource`, `TeachingToday` hanya bisa diakses oleh `super_admin`, `admin`, `teacher`
3. **Scope query** (`getEloquentQuery()`): teacher hanya melihat data dengan `staff_member_id` = ID dirinya sendiri
4. **Validasi eksekusi** (`LessonExecutionService.start()`): guru tidak bisa memulai sesi yang bukan miliknya

---

## File Terkait

### Migrations
- `database/migrations/2026_05_11_200001_create_curriculum_plans_table.php`
- `database/migrations/2026_05_11_200002_create_curriculum_plan_topics_table.php`
- `database/migrations/2026_05_11_200003_create_lesson_sessions_table.php`
- `database/migrations/2026_05_11_200004_create_lesson_session_materials_table.php`
- `database/migrations/2026_05_11_200005_create_lesson_session_assignments_table.php`

### Models
- `app/Models/CurriculumPlan.php`
- `app/Models/CurriculumPlanTopic.php`
- `app/Models/LessonSession.php`
- `app/Models/LessonSessionMaterial.php`
- `app/Models/LessonSessionAssignment.php`

### Services
- `app/Services/CurriculumPlanService.php` — bulk-apply + distributeTopics
- `app/Services/LessonExecutionService.php` — start / complete / cancel
- `app/Services/Notifications/LessonReminderNotifier.php` — WA + Email reminder

### Filament Resources & Pages
- `app/Filament/Resources/CurriculumPlans/CurriculumPlanResource.php`
- `app/Filament/Resources/LessonSessions/LessonSessionResource.php`
- `app/Filament/Pages/TeachingToday.php`

### Views
- `resources/views/filament/pages/teaching-today.blade.php`
- `resources/views/notifications/templates/lesson-reminder.blade.php`

### Commands
- `app/Console/Commands/DispatchLessonReminders.php`

### Seeder
- `database/seeders/CurriculumDemoSeeder.php`
