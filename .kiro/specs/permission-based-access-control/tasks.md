# Implementation Plan: Permission-Based Access Control Migration

## Overview

Rencana implementasi ini mengubah kontrol akses Admin_Panel (`/admin`) dari role hardcoded (`hasAnyRole()` di `canAccess()`/`canView()`) menjadi murni berbasis permission Filament Shield. Pendekatannya incremental dan aman dari lockout: pertama-tama config Shield diverifikasi, lalu seluruh permission/policy di-generate, baseline permission di-seed agar tidak ada role yang kehilangan akses, baru kemudian Manual_Access_Method dihapus per-entitas (Resource → Page → Widget → action-level gate). Data_Scoping dan penyembunyian ekskul dipertahankan independen dari permission. Implementasi menggunakan PHP (Laravel 12, Filament 4, Spatie Permission 7, Filament Shield 4) dengan Pest v3 untuk property-based dan example tests (database test SQLite + `RefreshDatabase`, minimal 100 iterasi per property).

## Tasks

- [x] 1. Verifikasi dan finalisasi konfigurasi Shield
  - [x] 1.1 Verifikasi/sesuaikan `config/filament-shield.php`
    - Pastikan `super_admin.enabled = true`, `super_admin.name = super_admin`, `super_admin.intercept_gate = before`
    - Pastikan `permissions.separator = ':'`, `permissions.case = pascal`, `permissions.generate = true`
    - Pastikan `policies.path = app_path('Policies')`, `policies.generate = true` dengan daftar method lengkap (viewAny, view, create, update, delete, deleteAny, restore, forceDelete)
    - Pastikan `pages.prefix = view` dengan `Dashboard::class` ter-exclude; `widgets.prefix = view` dengan `AccountWidget` dan `FilamentInfoWidget` ter-exclude
    - Pastikan `discovery.discover_all_resources/widgets/pages = false` agar entitas Tahfidz/portal tidak ter-generate
    - _Requirements: 1.3, 1.4, 1.5, 2.1, 2.2, 9.3_

  - [ ]* 1.2 Tulis config/smoke test untuk konfigurasi Shield
    - Assert `intercept_gate = before` dan entitas excluded (Dashboard, AccountWidget, FilamentInfoWidget) tidak menghasilkan permission
    - Assert flag discovery seluruhnya `false`
    - _Requirements: 1.3, 2.2, 9.3_

- [x] 2. Generate Policy dan Permission Shield
  - [x] 2.1 Jalankan `php artisan shield:generate --all` dan commit hasilnya
    - Hasilkan Policy untuk setiap Resource ke `app/Policies` (buat direktori bila belum ada)
    - Hasilkan Permission untuk setiap Resource/Page/Widget non-excluded ke tabel `permissions` dengan format pascal + separator `:`
    - Tambahkan skrip/command verifikasi yang membandingkan daftar entitas panel dengan permission yang terbentuk dan mencatat entitas yang gagal tanpa membatalkan proses
    - _Requirements: 1.1, 1.2, 1.4, 1.5, 1.6_

  - [x]* 2.2 Tulis integration test untuk hasil generasi
    - Assert file Policy untuk sample Resource ada dan permission sample (mis. `ViewAny:Quiz`, `View:JurnalMengajar`, `View:LessonProgressWidget`) tergenerate dengan penamaan benar
    - _Requirements: 1.1, 1.2, 1.4_

- [x] 3. Implementasi ShieldPermissionSeeder (baseline permission)
  - [x] 3.1 Buat `database/seeders/ShieldPermissionSeeder.php` dengan `map()` deklaratif
    - Definisikan `public static function map(): array` yang memetakan role→daftar nama permission, diturunkan dari `canAccess()` lama (admin, teacher, counselor, editor, piket, guru_ekstrakurikuler)
    - JANGAN sertakan `super_admin` di domain map; JANGAN sertakan permission Resource yang disembunyikan dari `guru_ekstrakurikuler`
    - _Requirements: 3.2, 7.2_

  - [x] 3.2 Implementasi `run()` dengan full replacement, fail-fast, dan preservasi role tak terkelola
    - Untuk tiap role terkelola, verifikasi keberadaan setiap permission lalu `syncPermissions()` (full replacement)
    - Fail-fast: lempar exception berisi nama permission pertama yang tidak ditemukan, hentikan assignment lanjutan
    - Hanya sinkronkan role yang ada di `map()`; jangan ubah role lain dan jangan assign `super_admin`
    - _Requirements: 3.1, 3.3, 3.4, 3.5_

  - [x]* 3.3 Tulis property test: seeder assigns exactly the declared map
    - **Property 2: Seeder assigns exactly the declared map and never touches Super_Admin**
    - **Validates: Requirements 3.1, 3.2**

  - [x]* 3.4 Tulis property test: idempotence under repetition
    - **Property 3: Seeder idempotence under repetition**
    - **Validates: Requirements 3.3**

  - [x]* 3.5 Tulis property test: preserve assignments of unmanaged roles
    - **Property 4: Seeder preserves assignments of unmanaged roles**
    - **Validates: Requirements 3.5**

  - [x]* 3.6 Tulis property test: fail-fast on first missing permission
    - **Property 5: Seeder fails fast on the first missing permission**
    - **Validates: Requirements 3.4**

  - [x]* 3.7 Tulis property test: ekskul map excludes hidden-resource permissions
    - **Property 6: Ekskul role map excludes hidden-resource permissions**
    - **Validates: Requirements 7.2**

- [x] 4. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 5. Migrasi Resource (hapus Manual_Access_Method)
  - [x] 5.1 Migrasi Resource admin-only non-scoped
    - Hapus `canAccess()` berbasis `hasAnyRole()` pada StudentResource, StudentPaymentResource, SchoolClassResource, NotificationLogResource, dan Resource sejenis lain yang admin-only
    - Biarkan akses ditentukan Policy Shield via `ViewAny:{Subject}`
    - _Requirements: 4.1, 4.2, 4.4_

  - [x] 5.2 Migrasi Resource ber-scope (teacher) dengan mempertahankan Data_Scoping
    - Hapus `canAccess()` pada QuizResource, QuestionBankResource, LessonSessionResource, CurriculumPlanResource, GradeResource, StudentAttendanceResource, ExamSessionResource, LeaveRequestResource
    - PERTAHANKAN override `getEloquentQuery()` (mis. `where('staff_member_id', ...)`) tanpa perubahan logika scoping
    - _Requirements: 4.1, 4.2, 4.4, 8.1, 8.3_

  - [x] 5.3 Migrasi Resource counselor/editor (termasuk yang ber-scope)
    - Hapus `canAccess()` pada StudentViolationResource, CounselingTicketResource, ClassMaterialResource, ClassAnnouncementResource, ClassAssignmentResource, ParentNoteResource, InternalAnnouncementResource
    - PERTAHANKAN `getEloquentQuery()` scoping pada Resource counselor/teacher yang relevan
    - _Requirements: 4.1, 4.2, 4.4, 8.1, 8.3_

  - [x]* 5.4 Tulis property test: surface access if and only if permission
    - **Property 1: Surface access if and only if permission**
    - **Validates: Requirements 4.2, 4.4, 5.2, 5.4, 6.2, 7.1, 11.1**

  - [x]* 5.5 Tulis property test: scoped query returns exactly the teacher's own records
    - **Property 8: Scoped query returns exactly the teacher's own records, independent of permissions**
    - **Validates: Requirements 8.1, 8.2, 8.3**

  - [x]* 5.6 Tulis property test: trait-bearing resources always hide guru_ekstrakurikuler
    - **Property 7: Trait-bearing resources always hide guru_ekstrakurikuler**
    - **Validates: Requirements 7.3, 7.4**

  - [x]* 5.7 Tulis HTTP/example test untuk akses Resource yang dimigrasi
    - Tanpa `ViewAny:{Subject}` → navigasi tersembunyi dan akses URL langsung 403; dengan permission → 200
    - _Requirements: 4.4, 11.2_

- [x] 6. Migrasi custom Page (hapus Manual_Access_Method)
  - [x] 6.1 Migrasi Page laporan
    - Hapus `canAccess()` dan `shouldRegisterNavigation()` berbasis `hasAnyRole()` pada JurnalMengajar, LaporanAbsensi, LaporanPenilaian, LaporanKasusSiswa
    - Biarkan akses & registrasi navigasi ditentukan permission `View:{Page}`
    - _Requirements: 5.1, 5.2, 5.4_

  - [x] 6.2 Migrasi Page input/utilitas
    - Hapus `canAccess()`/`shouldRegisterNavigation()` berbasis `hasAnyRole()` pada AbsensiHariIni, CetakKartuSiswa, TeachingToday, InputNilaiSesi, InputNilaiUjian
    - Pertahankan logika scoping internal (mis. pembatasan staff pada query) yang bukan otorisasi surface
    - _Requirements: 5.1, 5.2, 5.4_

  - [x]* 6.3 Tulis HTTP/example test untuk Page yang dimigrasi
    - Tanpa `View:{Page}` → akses langsung menghasilkan HTTP 403 (bukan 200) dan navigasi tersembunyi; dengan permission → 200
    - _Requirements: 5.3, 5.4_

- [x] 7. Migrasi Widget berbasis permission
  - [x] 7.1 Hapus `canView()` hardcoded pada LessonProgressWidget
    - Biarkan visibilitas ditentukan permission `View:LessonProgressWidget`
    - _Requirements: 6.1, 6.2_

  - [x]* 7.2 Tulis example test untuk render Widget
    - Tanpa permission → konten widget tidak dirender (boleh placeholder/nonaktif); dengan permission → konten muncul
    - _Requirements: 6.3_

- [x] 8. Selaraskan action-level gate InternalAnnouncement dengan permission
  - [x] 8.1 Ganti `->visible(fn () => hasAnyRole())` dengan pengecekan permission granular
    - Selaraskan EditAction/DeleteAction/DeleteBulkAction pada InternalAnnouncementsTable dan header actions pada ListInternalAnnouncements/EditInternalAnnouncement/ViewInternalAnnouncement dengan permission `Update:`/`Delete:` bila tersedia
    - Pastikan tidak menyebabkan lockout (fallback aman bila permission belum ada)
    - _Requirements: 4.1, 4.2_

  - [x]* 8.2 Tulis example test untuk visibilitas action InternalAnnouncement
    - Action muncul jika dan hanya jika role memiliki permission terkait
    - _Requirements: 4.2, 11.1_

- [x] 9. Checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

- [x] 10. Diagnostik transisi migrasi campuran
  - [x] 10.1 Implementasi pesan/diagnostik yang membedakan "entitas belum dimigrasi" dari "permission tidak dimiliki"
    - Tambahkan handler/logging yang memberi konteks pada error otorisasi selama migrasi bertahap
    - Pastikan entitas yang belum dimigrasi tetap berperilaku lama tanpa error otorisasi
    - _Requirements: 10.3, 10.4_

  - [x]* 10.2 Tulis test untuk jalur diagnostik dan panel kondisi campuran
    - Assert panel dengan sebagian entitas dimigrasi dan sebagian belum dapat dimuat tanpa error; pesan diagnostik sesuai
    - _Requirements: 10.2, 10.3, 10.4_

- [x] 11. Suite verifikasi akses per-role
  - [x] 11.1 Tulis test verifikasi akses terparametrisasi per-role
    - Parametrisasi role `admin`, `teacher`, `counselor`, `editor`, `piket`, `guru_ekstrakurikuler`: akses diizinkan iff role punya permission; `super_admin` diizinkan ke seluruh sample surface
    - _Requirements: 11.1, 11.3, 11.4, 2.1_

  - [x]* 11.2 Tulis test akses Shield Role Management
    - Hanya `super_admin` boleh mengakses `/admin/shield/roles/{id}/edit`; role non-super tanpa permission ditolak
    - _Requirements: 2.3_

  - [x]* 11.3 Tulis integration test Out_Of_Scope_Surfaces
    - Pastikan panel Tahfidz (`/tahfidz`) dan portal (`/portal`, `/portal/ortu`) tetap dapat diakses pengguna berwenang pasca-migrasi
    - _Requirements: 9.1, 9.2_

- [x] 12. Orkestrasi prosedur penerapan (deployment command)
  - [x] 12.1 Implementasi artisan command urutan deployment
    - Sediakan command yang menjalankan urutan: `shield:generate --all` → `db:seed --class=ShieldPermissionSeeder` → reset cache permission (`permission:cache-reset`) + cache aplikasi
    - Tambahkan gating: tahan langkah penghapusan/aktivasi mode permission bila seeder belum dijalankan di environment; sertakan langkah verifikasi pasca-deploy (super_admin + minimal satu role non-super)
    - _Requirements: 12.1, 12.3, 12.4, 10.1, 10.5_

  - [x]* 12.2 Tulis test untuk command deployment
    - Assert urutan langkah benar dan setelah reset cache permission, `can()` mencerminkan assignment terbaru
    - _Requirements: 12.2_

- [x] 13. Final checkpoint - Ensure all tests pass
  - Ensure all tests pass, ask the user if questions arise.

## Notes

- Tasks marked with `*` are optional (test-related) and can be skipped for a faster MVP, but property tests validate the universal correctness guarantees of the seeder and access rules.
- Setiap task mereferensikan klausa requirement spesifik untuk traceability.
- Property tests memetakan 1:1 ke Correctness Properties di design; setiap property dijalankan minimal 100 iterasi dengan tag `Feature: permission-based-access-control, Property {number}`.
- Urutan WAJIB: generate (task 2) → seed (task 3) → baru hapus Manual_Access_Method (task 5+), agar tidak ada lockout (Req 12.3).
- Checkpoint memastikan validasi inkremental sebelum melanjutkan ke fase berikutnya.

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1"] },
    { "id": 1, "tasks": ["1.2", "2.1"] },
    { "id": 2, "tasks": ["2.2", "3.1"] },
    { "id": 3, "tasks": ["3.2"] },
    { "id": 4, "tasks": ["3.3", "3.4", "3.5", "3.6", "3.7", "5.1", "5.2", "5.3", "6.1", "6.2", "7.1", "8.1", "10.1", "12.1"] },
    { "id": 5, "tasks": ["5.4", "5.5", "5.6", "5.7", "6.3", "7.2", "8.2", "10.2", "11.1", "11.2", "11.3", "12.2"] }
  ]
}
```
