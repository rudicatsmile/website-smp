# Pengumuman Internal Guru

Dashboard pengumuman internal di Filament panel dengan kategori (rapat/surat tugas/agenda dinas/umum), prioritas, lampiran, target audience per role/staf, fitur acknowledge, dan notifikasi Filament database.

## Cakupan
- CRUD pengumuman oleh `admin`/`editor`/`super_admin`.
- Role baru `teacher` yang bisa login Filament panel **terbatas** (hanya melihat pengumuman yang ditargetkan padanya, dapat acknowledge).
- Notifikasi database (Filament bell) saat pengumuman dipublish.
- Tidak ada halaman publik (akses internal saja, sesuai requirement).

## Database

### Migration `internal_announcements`
- `id`
- `user_id` (FK ke users, author; nullOnDelete)
- `title` string
- `slug` string unique
- `body` longText (RichEditor HTML)
- `category` string (`rapat`, `surat_tugas`, `agenda_dinas`, `umum`)
- `priority` string (`normal`, `penting`, `urgent`)
- `target_roles` json nullable — array string: `['semua_guru', 'staf', 'wali_kelas']`
- `target_staff_ids` json nullable — array of staff_member ids untuk pengiriman spesifik
- `attachments` json nullable — array `[{name, path, size, mime}]`
- `is_pinned` boolean default false
- `published_at` datetime nullable (null = draft)
- `expires_at` datetime nullable
- `is_active` boolean default true
- timestamps
- Index: `(category, published_at)`, `(priority)`, `(is_pinned)`

### Migration `internal_announcement_acknowledgements`
- `id`
- `internal_announcement_id` (FK cascade)
- `user_id` (FK cascade)
- `acknowledged_at` datetime
- timestamps
- Unique: `(internal_announcement_id, user_id)`

## Models

### `App\Models\InternalAnnouncement`
- Fillable + casts (target_roles array, target_staff_ids array, attachments array, datetimes, bools).
- Constants: `CATEGORIES`, `PRIORITIES`, `TARGET_ROLES`.
- Relations: `author()` BelongsTo User, `acknowledgements()` HasMany.
- Scopes: `published()` (published_at <= now & not expired), `active()`, `pinned()`, `forUser(User $user)` — filter berdasarkan target_roles vs role user dan/atau target_staff_ids vs user→staffMember (jika ada).
- Accessors: `category_label`, `priority_label`, `priority_color`, `is_acknowledged_by(User)`.

### `App\Models\InternalAnnouncementAcknowledgement`
- BelongsTo announcement & user.

### Tambahan di `User`
- `acknowledgedAnnouncements(): BelongsToMany` (pivot acknowledgements).
- Method `staffMember()` HasOne ke `StaffMember` melalui `email` matching (atau via `user_id` baru). **Keputusan**: tambah kolom `user_id` nullable di `staff_members` agar relasi 1:1 jelas.

### Migration tambahan: `add_user_id_to_staff_members`
- `staff_members.user_id` foreignId nullable, constrained, nullOnDelete.

### Tambahan di `StaffMember`
- BelongsTo `user()`.

## Role baru `teacher`
- Tambahkan ke `RoleSeeder`: `'super_admin', 'admin', 'editor', 'contributor', 'teacher'`.
- Update `User::canAccessPanel()` agar `teacher` juga boleh masuk panel.

## Filament

### Resource `InternalAnnouncementResource`
- Visible untuk `admin`/`editor`/`super_admin` (full CRUD) dan `teacher` (read-only + acknowledge).
- `canCreate/canEdit/canDelete` mengembalikan false untuk role `teacher`.
- `getEloquentQuery` di-scope `forUser(auth()->user())` jika user adalah `teacher`.
- Form (sections):
  1. **Konten**: title (live slug), category Select, priority Select, body RichEditor.
  2. **Target**: target_roles CheckboxList, target_staff_ids Select multiple relationship ke staff_members (searchable).
  3. **Lampiran**: attachments FileUpload multiple (`disk: public`, `directory: announcements`, max 10MB per file, accept pdf/doc/xls/img).
  4. **Pengaturan**: is_pinned Toggle, is_active Toggle, published_at DateTimePicker, expires_at DateTimePicker.
- Table:
  - Columns: priority badge (colored), pinned icon, title (searchable), category badge, author.name, published_at, ack count (counts), is_active.
  - Filters: SelectFilter category, priority, TernaryFilter is_pinned, is_active.
  - **Custom action `Acknowledge`** (record action) muncul jika user belum ack & terupable; klik membuat row di acknowledgements & success notification.
  - Default sort: `is_pinned desc`, `priority` (urgent first via raw), `published_at desc`.
- NavigationLabel: "Pengumuman Internal", icon `megaphone`, sort 30.
- NavigationGroup: tetap default (untuk konsistensi).
- NavigationBadge: tampilkan jumlah pengumuman aktif yang belum di-ack oleh current user (untuk teacher) atau total aktif (untuk admin).

### Page `ViewInternalAnnouncement` (custom view page)
- Custom page selain Edit, untuk teacher membaca detail (full body + lampiran + tombol Acknowledge prominent).
- Header: badges priority, category, pinned, published_at, expires_at, author, ack count.
- Tombol unduh lampiran via Storage download.
- Untuk admin/editor: link ke Edit dari ViewPage.

### Notifikasi
- Observer `InternalAnnouncementObserver` — pada `created` (jika published_at <= now) atau `updated` (saat published_at baru di-set dari null), kirim Filament `Notification::make()` ke target users:
  - Resolve target users: gabungan dari (a) users yang punya role di `target_roles` mapping (`semua_guru` → role `teacher`; `staf` → users with staff_member; `wali_kelas` → custom flag/category) dan (b) users yang punya `staff_member` di `target_staff_ids`.
  - Send via `->sendToDatabase($user)` dengan body singkat + URL ke ViewPage.
- Register observer di `AppServiceProvider`.

## Seeder `InternalAnnouncementSeeder`
- 5 sample: 1 rapat (priority penting, pinned), 1 surat_tugas (urgent, target staff spesifik), 1 agenda_dinas (normal, semua_guru), 1 umum (normal), 1 expired untuk testing.
- Daftarkan di `DatabaseSeeder` setelah `StaffScheduleSeeder`.

## Demo Teacher User
- Tambah di `AdminUserSeeder` atau seeder baru: 1 user demo `teacher@example.com` / password `password`, assign role `teacher`, hubungkan ke salah satu `StaffMember` via `user_id`.

## QA Checklist
- Migrasi & seeder berjalan tanpa error.
- Login admin → menu "Pengumuman Internal" muncul, dapat CRUD, set target, upload lampiran multi-file, set pinned/expires.
- Login teacher → panel muncul terbatas; hanya melihat pengumuman yang ditargetkan; dapat acknowledge; tidak bisa create/edit/delete.
- Notifikasi bell muncul untuk teacher saat ada pengumuman baru.
- Acknowledge tersimpan ke pivot dan ack count bertambah; tombol acknowledge hilang setelah di-klik.
- Pengumuman expired tidak muncul lagi untuk teacher.
- Pinned & priority urgent tampil paling atas dalam tabel.
- Tidak ada error linter PHP/Blade.
