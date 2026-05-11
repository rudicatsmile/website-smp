# Portal Tugas & Pengumuman Kelas

Implementasi portal siswa (Livewire publik) dan panel guru (Filament) untuk mengelola kelas, siswa, tugas, pengumuman kelas, dan submission, terintegrasi dengan `User`/`StaffMember`/`MaterialCategory` yang sudah ada.

## Ringkasan Fitur

- **Guru/Admin (Filament)**: CRUD `SchoolClass`, `Student`, `ClassAssignment`, `ClassAnnouncement`; lihat + nilai `AssignmentSubmission`.
- **Siswa (Livewire publik)**: Dashboard di `/portal`, melihat tugas & deadline, mengunduh lampiran guru, upload submission, melihat pengumuman kelas & status nilai.
- **Role baru**: `student` (tidak bisa akses Filament).

## Struktur Data (Migrations)

1. **`school_classes`** — id, grade (7/8/9), section (A/B/...), name ("7A"), academic_year ("2025/2026"), homeroom_teacher_id → `staff_members`, is_active, timestamps, unique(grade, section, academic_year).
2. **`students`** — id, user_id nullable → `users`, nis unique, nisn nullable, name, slug, school_class_id → `school_classes`, gender, birth_date, photo, parent_name, parent_phone, is_active, timestamps.
3. **`class_assignments`** — id, school_class_id, material_category_id (mapel), staff_member_id (guru), title, slug, description (rich), attachments (json array), due_at datetime, max_score (default 100), is_published, published_at, timestamps.
4. **`class_announcements`** — id, school_class_id nullable (null=semua kelas), staff_member_id, title, body (rich), attachments (json), pinned, is_published, published_at, expires_at nullable, timestamps.
5. **`assignment_submissions`** — id, class_assignment_id, student_id, files (json), note, submitted_at, score nullable, feedback, graded_at, graded_by → `staff_members`, timestamps, unique(class_assignment_id, student_id).
6. **`class_materials`** — id, school_class_id nullable, material_category_id, staff_member_id, title, description, file path, size, is_published, timestamps. (Materi unduh khusus kelas).

## Models

- `SchoolClass` (relations: homeroomTeacher, students, assignments, announcements)
- `Student` (relations: user, class, submissions; accessor `photoUrl`)
- `ClassAssignment` (relations: class, subject, teacher, submissions; scope `published`, `open`, `overdue`; accessor `is_overdue`, `status`)
- `ClassAnnouncement` (relations: class, teacher; scope `published`, `active`)
- `AssignmentSubmission` (relations: assignment, student, grader; accessor `is_late`, `status`)
- `ClassMaterial` (relations: class, subject, teacher)
- Update `User`: add `student()` HasOne relation; role check `student` not allowed in `canAccessPanel`.

## Filament Resources (Guru/Admin)

- **SchoolClassResource** — CRUD kelas (admin & super_admin).
- **StudentResource** — CRUD siswa; aksi "Generate Akun Login" (buat `User` dgn role `student`, email = `nis@smpalwathoniyah9.sch.id`, password default).
- **ClassAssignmentResource** — form (mapel, kelas, deadline, lampiran FileUpload multi, deskripsi RichEditor, publish toggle); guru hanya melihat tugas miliknya; tabel dgn kolom status (open/closed/graded), jumlah submission.
  - Relasi `SubmissionsRelationManager` untuk lihat + grade submission siswa (score, feedback).
- **ClassAnnouncementResource** — CRUD pengumuman kelas; guru hanya kelas yg diampu atau global (admin).
- **ClassMaterialResource** — upload materi per kelas/mapel.
- Permission scoping via `staff_member_id = Auth::user()->staffMember?->id` untuk role `teacher`.

## Portal Siswa (Livewire Publik)

Route prefix `/portal` dgn middleware `auth` + `role:student`:

- `/portal/login` — halaman login khusus siswa (redirect ke `/portal` bila sudah login, redirect admin/teacher ke `/admin`).
- `/portal` — dashboard: card ringkasan (tugas aktif, tugas overdue, pengumuman terbaru), list tugas terbaru, pengumuman pinned.
- `/portal/tugas` — index tugas kelas siswa, filter status (semua/belum dikerjakan/sudah/terlambat), sort by deadline.
- `/portal/tugas/{assignment}` — detail tugas: deskripsi, lampiran guru (download), form upload submission, lihat score/feedback bila sudah dinilai.
- `/portal/pengumuman` — list pengumuman (kelas siswa + global), pinned di atas.
- `/portal/pengumuman/{announcement}` — detail pengumuman.
- `/portal/materi` — list materi guru per mapel (download).
- `/portal/profil` — lihat profil siswa + ganti password.

UI: Tailwind, responsive, card-based, badge warna untuk status (open=emerald, due-soon=amber, overdue=red, graded=sky), layout bersih cocok siswa SMP.

## Role, Auth, Middleware

- Tambah role `student` di `RoleSeeder`.
- `User::canAccessPanel()` — return false jika hanya punya role `student`.
- Route `/portal` dgn middleware grup: `auth`, `role:student|super_admin|admin` (admin bisa preview).
- Redirect setelah login: admin/editor/teacher → `/admin`, student → `/portal`, selain itu logout.

## Seeder

- `SchoolClassSeeder` — 6 kelas (7A, 7B, 8A, 8B, 9A, 9B) tahun ajaran aktif.
- `StudentSeeder` — 20 siswa demo tersebar di kelas, plus 1 demo akun: `siswa@smpalwathoniyah9.sch.id` / `password` (di kelas 7A).
- `ClassAssignmentSeeder` — 8 tugas contoh (2 overdue, 4 open, 2 closed).
- `ClassAnnouncementSeeder` — 4 pengumuman (2 global, 2 per kelas).
- `ClassMaterialSeeder` — 5 materi contoh.
- Register di `DatabaseSeeder`.

## Navigation & Integration

- Filament: group **Akademik** dengan icon akademik — Kelas, Siswa, Tugas Kelas, Pengumuman Kelas, Materi Kelas.
- Tambah link di navbar website: "Portal Siswa" → `/portal`.
- Badge jumlah tugas belum dikerjakan di portal siswa.

## Testing Manual

1. `php artisan migrate` & `db:seed --class=SchoolClassSeeder` dst.
2. Login guru `teacher@smpalwathoniyah9.sch.id` → Filament → buat Tugas Kelas → publish.
3. Login siswa `siswa@smpalwathoniyah9.sch.id` / `password` di `/portal/login` → lihat tugas → upload submission.
4. Login guru → Relation Manager → beri nilai & feedback.
5. Login siswa → lihat nilai di detail tugas.
6. Admin bisa CRUD semua data & generate akun siswa.

## Catatan Teknis

- Reuse `Storage::disk('public')` untuk lampiran & materi, folder: `class-assignments/`, `class-announcements/`, `class-materials/`, `submissions/`.
- Batas file submission: 10MB, format pdf/doc/docx/jpg/png/zip.
- Gunakan `DatabaseNotification` Filament untuk notifikasi guru saat siswa submit (opsional, bila sempat).
- Gunakan slug unik untuk assignment & announcement agar URL ramah.
