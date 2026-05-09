# Konseling Online (BK Digital)

Bangun fitur pengaduan/konsultasi ke Guru BK dengan dua jalur (publik anonim via kode tiket + thread berbalas untuk siswa login), kategori & prioritas untuk triage, status tracking, lampiran opsional, dan role Filament baru `counselor` untuk mengelola tiket di admin.

## 1. Cakupan

- **Dua jalur pengaduan**:
  - **Publik / anonim**: halaman `/bk` — siapa saja (tanpa login) bisa kirim. Sistem generate **kode tiket** unik (mis. `BK-7F3K9A`). Pelapor cek balasan via `/bk/status?kode=...` (tidak ada akun — hanya kode yang jadi kunci).
  - **Portal siswa (login)**: `/portal/bk` — daftar pengaduan yang dibuat siswa + thread balasan dua arah. Opsi toggle *"Sembunyikan identitas"* (counselor tetap tahu siswa asli di sistem, tapi UI-nya dihide; untuk v1 kita buat sederhana: di portal nama tercatat karena untuk histori).
- **Kategori**: akademik, pribadi, keluarga, pertemanan/bullying, kesehatan, ekonomi, lainnya.
- **Prioritas**: rendah, sedang, tinggi, urgent (default: sedang; counselor bisa ubah).
- **Status**: `new`, `in_progress`, `resolved`, `closed`.
- **Lampiran**: opsional, maks 3 file (image/pdf/doc), 5MB masing-masing, disimpan di `storage/app/public/counseling`.
- **Role baru** `counselor` (Spatie). Dapat akses menu Filament "Konseling BK". `super_admin` & `admin` juga punya akses.

## 2. Database Migrations

1. `counseling_tickets`
   - `id`, `code` (unique, format `BK-XXXXXX`), `user_id` (nullable — null utk publik anonim),
   - `reporter_name` (nullable — publik isi nama opsional), `reporter_contact` (nullable — email/WA),
   - `category` (string), `priority` (string), `status` (string default `new`),
   - `subject` (string), `body` (longText), `attachments` (json),
   - `channel` (enum: `public` | `portal`),
   - `is_anonymous` (bool — true untuk publik tanpa identitas),
   - `assigned_to` (nullable FK → `staff_members`),
   - `resolved_at` (nullable), `last_activity_at` (index),
   - `timestamps`, index `[status, priority]`, `[assigned_to]`, `[code]`.
2. `counseling_messages` — thread balas-membalas.
   - `id`, `counseling_ticket_id`, `sender_type` (enum: `student` | `counselor` | `anonymous`),
   - `user_id` (nullable), `staff_member_id` (nullable),
   - `body` (longText), `attachments` (json), `is_internal` (bool — catatan privat counselor),
   - `timestamps`, index `[counseling_ticket_id, created_at]`.

## 3. Role & Seeder

- Tambah role `counselor` di `RoleSeeder`.
- Beri `super_admin` dan `admin` izin default ke resource.
- Di `CounselingSeeder` (baru): buat 1 user demo counselor (`bk@smpalwahoniyah9.sch.id` / `password`) dengan `StaffMember` terkait, dan 3 tiket demo (1 publik anonim `new`, 1 portal `in_progress` dengan 2 balasan, 1 `resolved`).

## 4. Models

- `CounselingTicket`
  - Fillable sesuai kolom. Casts: `attachments` array, `resolved_at`/`last_activity_at` datetime, `is_anonymous` bool.
  - Relasi: `user` (User), `assignee` (StaffMember), `messages` (HasMany, default `orderBy created_at`).
  - Scopes: `open()`, `forStudent($user)`, `byCode($code)`.
  - Accessor `status_label`, `priority_color`, `category_label`.
  - Boot: auto-generate `code` saat creating (format `BK-` + 6 char upper alfanum, retry jika clash).
- `CounselingMessage`
  - Fillable, casts `attachments` array, `is_internal` bool.
  - Relasi: `ticket`, `user`, `staffMember`.
  - Scope `public()` (bukan internal — untuk siswa/pelapor).

## 5. Routes

`routes/web.php`:

```
// Publik
Route::get('/bk', \App\Livewire\Pages\CounselingForm::class)->name('bk.form');
Route::get('/bk/status', \App\Livewire\Pages\CounselingStatus::class)->name('bk.status');

// Portal (auth)
Route::middleware('auth')->prefix('portal')->name('portal.')->group(function () {
    Route::get('/bk', \App\Livewire\Portal\CounselingIndex::class)->name('counseling.index');
    Route::get('/bk/buat', \App\Livewire\Portal\CounselingCreate::class)->name('counseling.create');
    Route::get('/bk/{ticket}', \App\Livewire\Portal\CounselingShow::class)->name('counseling.show');
});
```

Tambahkan menu **BK / Konseling** di header portal.

## 6. Halaman Publik (Livewire)

- **`CounselingForm` (`/bk`)** — landing + form submit.
  - UI: hero menenangkan (warna teal/emerald lembut), copy "Ceritakan pelan-pelan, kami siap mendengar. Kerahasiaan terjaga."
  - Field: kategori, subject, body (textarea 6 baris), nama opsional, kontak (email/WA) opsional, upload lampiran (maks 3).
  - Submit → generate kode tiket, simpan `channel=public`, `is_anonymous=true` jika nama kosong, tampilkan halaman sukses dengan kode tiket yang mencolok + tombol copy + tautan ke `/bk/status?kode=...`.
- **`CounselingStatus` (`/bk/status`)** — input kode tiket → tampilkan detail tiket + thread balasan publik (bukan `is_internal`).
  - Di halaman ini, pelapor bisa kirim balasan tambahan (tanpa login) → tercatat sebagai `sender_type=anonymous`.
  - Throttle submit & cek kode (`throttle:10,1`).

## 7. Halaman Portal Siswa (Livewire)

- **`CounselingIndex` (`/portal/bk`)** — daftar tiket milik siswa (status badge + last activity + unread indicator jika ada balasan baru dari counselor).
- **`CounselingCreate` (`/portal/bk/buat`)** — form mirip publik, tapi `user_id` terisi otomatis dan `channel=portal`.
- **`CounselingShow` (`/portal/bk/{ticket}`)** — thread chat:
  - Bubble kiri (counselor), bubble kanan (siswa). Pesan `is_internal` disembunyikan dari siswa.
  - Kotak balasan bawah. Siswa bisa close ticket (status → `closed`).

## 8. Filament Resource — `CounselingTicketResource`

- Group: **Komunikasi** (atau `Akademik` jika konsisten dengan resource lain — pilih **"Konseling BK"** sebagai grup baru agar terpisah).
- Navigation: icon `heroicon-o-heart`, label **Konseling BK**, badge jumlah tiket `new`.
- **Form**: subject, kategori, prioritas, status, assignee (StaffMember aktif), body (read-only di edit), attachments read-only, pelapor info (readonly block untuk publik: nama, kontak, kode tiket).
- **Table columns**: `code`, `subject` (limit), `category` (badge), `priority` (badge color), `status` (badge), `channel` (badge), `reporter` (accessor: user->name atau reporter_name atau "Anonim"), `assignee.name`, `last_activity_at` (since), `messages_count`.
- **Filters**: status, priority, category, channel, assigned_to (only mine).
- **Relation Manager `MessagesRelationManager`**: daftar pesan thread + action **Balas** (textarea + attachments + toggle `is_internal` — internal tidak terlihat pelapor). Setiap kirim balasan counselor:
  - `sender_type=counselor`, isi `staff_member_id` dari `auth()->user()->staffMember`.
  - Update `last_activity_at` tiket.
  - Jika status masih `new`, otomatis ke `in_progress`.
- **Bulk action**: tandai resolved.
- Akses: `super_admin`, `admin`, `counselor`. Counselor hanya melihat tiket yang `assigned_to` dirinya **atau** belum di-assign (inbox umum) — scope via `getEloquentQuery`.

## 9. Notifikasi

- Pada submit tiket baru (publik atau portal) → Filament `Notification::make()->send()` ke semua user dengan role `counselor` (via `sendToDatabase`). Subject: "Tiket baru: {code} ({priority})".
- Pada balasan counselor → (opsional) kirim email ke `reporter_contact` jika berisi email — sederhana via queued mailable. **Skip di v1** untuk fokus fungsi inti.

## 10. Keamanan & Privasi

- **CSRF & throttle** pada form publik (10 req/menit per IP).
- Kode tiket 6 char alphanumeric upper (≈2.1M kombinasi) — cukup sulit ditebak untuk v1; dokumen sarankan user simpan baik-baik.
- Data `reporter_contact` ditampilkan **hanya** ke counselor & admin; tidak pernah keluar ke publik.
- Pesan `is_internal=true` di relation manager tampak badge "Catatan Internal" dan di-exclude dari query portal/publik.

## 11. Seeder & Register

- Tambah `CounselingSeeder` di `DatabaseSeeder` setelah `QuizContentSeeder`.
- Update `RoleSeeder` untuk insert role `counselor` (jika belum ada).

## 12. Verifikasi

1. `php artisan migrate`.
2. `php artisan db:seed --class=CounselingSeeder`.
3. Publik: buka `http://127.0.0.1:8000/bk` → isi form → dapat kode `BK-XXXXXX` → cek di `/bk/status`.
4. Siswa login: menu **BK** di header portal → buat tiket baru → balas thread.
5. Login counselor `bk@smpalwahoniyah9.sch.id` → menu **Konseling BK** di Filament → balas tiket (toggle internal), ubah status, assign diri sendiri.
6. Verifikasi counselor hanya melihat tiket miliknya / inbox.

## 13. Out of Scope (v1)

- Realtime chat (pakai polling/refresh manual saja).
- Email/WA notifikasi otomatis.
- Lampiran dari balasan anonim publik (hanya di pengajuan awal).
- Riwayat audit log perubahan status.
- Analitik tren konseling.
