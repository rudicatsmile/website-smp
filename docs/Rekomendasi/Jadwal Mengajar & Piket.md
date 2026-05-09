# Jadwal Mengajar & Piket

Fitur jadwal mingguan berulang untuk mengajar/piket/rapat per guru dengan grid view publik, terintegrasi ke halaman kalender utama dan profil guru.

## Cakupan
- **Mingguan berulang** untuk mengajar/piket/rapat reguler (recurring per `day_of_week`).
- **Event one-off** (rapat khusus, agenda dinas) tetap memakai `SchoolEvent` (kategori `rapat-guru` ditambahkan) — tidak ada duplikasi data.
- Halaman publik `/jadwal` untuk transparansi.
- Tab "Jadwal Mingguan" di `/kalender`.
- Section "Jadwal Mingguan" di profil guru `/guru/{slug}`.

## Database

### Migration `staff_schedules`
- `id`
- `staff_member_id` (FK, cascade on delete)
- `material_category_id` (FK nullable, hanya jika type=mengajar)
- `type` enum: `mengajar`, `piket`, `rapat`, `lainnya`
- `day_of_week` tinyint (1=Senin … 6=Sabtu, 0=Minggu)
- `start_time` time
- `end_time` time
- `period` string nullable (mis. "Jam ke-1", "Jam ke-2") — opsional untuk display
- `class_name` string nullable (mis. "7A", "8B") — relevan untuk type=mengajar/piket-kelas
- `location` string nullable (mis. "Ruang Guru", "Lab IPA")
- `notes` string nullable
- `color` string default `emerald`
- `effective_from` date nullable, `effective_until` date nullable (opsional, untuk semester aktif)
- `academic_year` string nullable (mis. `2025/2026`)
- `semester` enum nullable: `1`, `2`
- `is_active` boolean default true
- `order` int
- timestamps
- Index: `(day_of_week, start_time)`, `(staff_member_id, day_of_week)`, `(type, is_active)`

## Model `App\Models\StaffSchedule`
- Fillable + casts (date, time, bools, ints).
- Relasi `staff()` → StaffMember, `subject()` → MaterialCategory (alias material_category).
- Scopes: `active()`, `forDay($day)`, `byType($type)`, `inEffect()` (cek effective_from/until).
- Accessor: `day_label`, `type_label`, `time_range`, `display_title` (gabungan kelas + mapel atau tipe).
- Constant `DAYS = [1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu', 0=>'Minggu']`.
- Constant `TYPES = ['mengajar'=>'Mengajar', 'piket'=>'Piket', 'rapat'=>'Rapat Rutin', 'lainnya'=>'Lainnya']`.

## Tambahan di `StaffMember`
- Relasi `schedules(): HasMany(StaffSchedule)` ordered by day_of_week, start_time.

## Seeder `StaffScheduleSeeder`
- Generate 12–18 entri sample: gabungan mengajar (Senin–Jumat), piket (Sabtu / 1–2 hari), rapat rutin guru (Senin pagi).
- Pakai guru & material categories yang sudah ada dari seeder sebelumnya.
- Daftarkan di `DatabaseSeeder` setelah `MaterialSeeder`.

## Filament Resource `StaffScheduleResource`
- Form sections (`Filament\Schemas\Components\Section`):
  1. **Detail Jadwal**: type (Select), staff_member_id (Select relationship), material_category_id (Select, visible jika type=mengajar), class_name, location.
  2. **Waktu**: day_of_week (Select 0–6), period, start_time, end_time.
  3. **Periode Berlaku**: academic_year, semester, effective_from, effective_until.
  4. **Tampilan**: notes (Textarea), color, order, is_active.
- Table: type badge, staff.name, day label, time_range, subject.name, class_name, location, is_active, edit/delete.
- Filters: SelectFilter type, day_of_week, staff_member_id (searchable), academic_year, TernaryFilter is_active.
- NavigationGroup: tidak set (sesuai pola resource lain agar konsisten).
- NavigationLabel: "Jadwal Guru".

## Tambahan di `SchoolEventResource` (existing)
- Tambahkan opsi kategori `rapat-guru` di SchoolEventForm select (current options: umum/akademik/ekstrakurikuler/libur).
- Tidak perlu migrasi karena `category` sudah `string`.

## Routes (`routes/web.php`)
- `/jadwal` → Livewire `StaffScheduleIndex`, name `jadwal.index`.

## Livewire `App\Livewire\Pages\StaffScheduleIndex`
- Properties (Url queryString):
  - `view` (default `grid`) — `grid` (matrix hari × waktu) atau `list` (per guru).
  - `day` ('' = semua), `type` ('' = semua), `staffId` (null), `search`.
- `render()`: ambil semua `StaffSchedule::active()->inEffect()` dengan eager `staff`, `subject`, dengan filter sesuai.
- Sediakan: list days, list types, list staff, dan untuk grid kumpulan jam unik (sorted) untuk membentuk header baris.

## Blade `resources/views/livewire/pages/staff-schedule-index.blade.php`
- Single root `<div>`.
- `<x-site.page-hero key="jadwal" title="Jadwal Mengajar & Piket" .../>`.
- Filter bar: search guru/mapel, dropdown hari, dropdown tipe, dropdown guru, toggle view grid/list.
- **Grid view**: header kolom = nama hari, baris = slot waktu unik. Setiap cell menampilkan badge entri jadwal (warna sesuai `color`/`type`), berisi guru, mapel/kelas, lokasi.
- **List view**: kelompok berdasar hari → daftar item (guru, time_range, type badge, mapel/kelas, lokasi).
- Empty state.

## Tambahan di `/kalender` (CalendarPage)
- Tambah tab/section "Jadwal Mingguan" di view `livewire.calendar-page` dengan link ke `/jadwal` (atau embed mini grid mingguan rapat-guru).
- **Minimal**: tambahkan di sidebar/atas halaman kalender card "Lihat Jadwal Mingguan Guru →" yang link ke `/jadwal` agar tidak menambah kompleksitas Livewire calendar yang ada.
- Update `categoryFilter` di `CalendarPage` agar mendukung opsi `rapat-guru` (di view `livewire.calendar-page`, jika ada select category).

## Tambahan di Profil Guru `staff-show`
- Section "Jadwal Mingguan" pada `staff-show.blade.php` (di bawah info kontak / di sidebar):
  - Tampilkan `$staff->schedules` dikelompokkan per hari, urut jam.
  - Format: badge tipe + jam + mapel (kalau ada) + kelas + lokasi.
  - Skip jika kosong.
- Update `StaffShow` Livewire untuk eager load `schedules.subject`.

## PageHeroSeeder
- Tambah entry `key=jadwal`, title "Jadwal Mengajar & Piket", subtitle, icon `clock`.

## Navbar
- Tambah link **"Jadwal"** di skin `education` dan `milleneal` (desktop + mobile), setelah link Materi.

## QA Checklist
- Migrasi & seeder berjalan tanpa error.
- Filament: CRUD StaffSchedule berfungsi; Select material_category hanya wajib saat type=mengajar (atau opsional saja, validation soft).
- `/jadwal` menampilkan grid + list view, filter & search bekerja, queryString sinkron.
- `/guru/{slug}` menampilkan jadwal mingguan guru tersebut.
- `/kalender` menampilkan link/CTA ke jadwal; kategori `rapat-guru` dapat dibuat dari Filament SchoolEvent dan tampil di kalender.
- Navbar link "Jadwal" tampil di kedua skin.
- Responsive di mobile (grid scroll horizontal jika perlu).
- Tidak ada error linter PHP/Blade.
