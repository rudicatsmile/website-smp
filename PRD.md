# Product Requirements Document (PRD)
## Content Management System (CMS) SMP Al Wahoniyah 9

> Dokumen ini menjelaskan kebutuhan produk, ruang lingkup, dan kriteria keberhasilan untuk pengembangan CMS sekolah **SMP Al Wahoniyah 9**.

---

## 1. Identitas Produk

| Item | Keterangan |
|---|---|
| **Nama Aplikasi** | Content Management System (CMS) SMP Al Wahoniyah 9 |
| **Pemilik** | SMP Al Wahoniyah 9 |
| **Jenis** | Web Application (Single Laravel App) |
| **Versi Dokumen** | 1.0 |
| **Bahasa Default** | Bahasa Indonesia |
| **Timezone** | Asia/Jakarta |

### 1.1 Deskripsi Singkat
CMS berbasis web yang digunakan oleh SMP Al Wahoniyah 9 untuk mengelola seluruh konten website sekolah (profil, berita, galeri, fasilitas, akademik, unduhan, kontak) serta menyediakan layanan **Sistem Penerimaan Murid Baru (SPMB) online** bagi calon siswa.

### 1.2 Tujuan
1. Memudahkan publikasi informasi sekolah kepada siswa, orang tua, dan masyarakat umum.
2. Menyediakan kanal pendaftaran SPMB online yang efisien.
3. Sentralisasi pengelolaan konten oleh staf sekolah tanpa perlu ngoding.
4. Meningkatkan citra digital sekolah dan SEO.

### 1.3 Target Pengguna
- **Pengunjung publik**: orang tua, calon siswa, masyarakat umum.
- **Calon siswa**: pengisi formulir SPMB.
- **Operator/Editor sekolah**: staf TU/humas yang mengelola konten.
- **Super Admin**: kepala TU/IT yang mengelola seluruh sistem.

---

## 2. Tujuan Bisnis & KPI

| KPI | Target |
|---|---|
| Konten dipublikasikan oleh non-developer | 100% |
| Waktu rilis berita sejak draft → publish | < 5 menit |
| Pendaftar SPMB via online | ≥ 80% dari total pendaftar |
| Uptime aplikasi | ≥ 99% |
| PageSpeed mobile score | ≥ 80 |

---

## 3. Stakeholder & Role

| Role | Akses |
|---|---|
| **Super Admin** | Full access — semua modul, user management, settings |
| **Editor** | CRUD seluruh modul konten (berita, slider, gallery, download, facility, academic), tidak bisa kelola user/setting |
| **Contributor** | Buat berita berstatus **draft** saja; tidak bisa publish |
| **Public** | Mengakses halaman frontend, mengirim form Contact & SPMB |

Implementasi role menggunakan **`spatie/laravel-permission`** + Policy Filament.

---

## 4. Arsitektur Sistem

- **Single Laravel Application** (Laravel 12).
- **Admin Panel**: Filament v4 di route `/admin`.
- **Frontend Public**: Laravel Livewire v3 + Blade + TailwindCSS v4.
- **Database**: MariaDB (`laravel_smp_v3`).
- **Storage**: Local public disk (`storage/app/public` → symlink `public/storage`).
- **Mail**: SMTP (config via `.env`), queue worker untuk pengiriman async.
- **Cache**: file/redis (opsional).
- **Search (opsional)**: Laravel Scout + database driver.

### 4.1 Konfigurasi Environment (sesuai permintaan)
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_smp_v3
DB_USERNAME=root
DB_PASSWORD=
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta
```

---

## 5. Fitur & Modul

Setiap modul memiliki:
- **Backend**: CRUD di Filament Resource (form, tabel, filter, bulk action, policy).
- **Frontend**: tampilan publik via Livewire/Blade.

### 5.1 Slider / Hero
- Field: `title`, `subtitle`, `image`, `link_url`, `link_text`, `order`, `is_active`.
- Frontend: tampil di Home sebagai carousel.
- Validasi: gambar `jpg/png/webp`, max 2 MB, rasio rekomendasi 16:9.

### 5.2 Berita (News)
- Field: `title`, `slug` (auto, unik), `category_id`, `tags[]`, `excerpt`, `body` (rich text), `thumbnail`, `author_id`, `published_at`, `status` (`draft|published|archived`), `views`.
- Relasi: `belongsTo NewsCategory`, `belongsTo User`, `belongsToMany Tag`.
- Frontend: list paginated + filter kategori/tag, halaman detail, related news, view counter.
- SEO: meta title & description per artikel.

### 5.3 Gallery
- Struktur: **Album** → multi **Item (foto)**.
- Field Album: `title`, `slug`, `cover`, `description`, `published_at`.
- Field Item: `gallery_id`, `image`, `caption`, `order`.
- Frontend: grid album → detail album dengan lightbox.

### 5.4 Download
- Field: `title`, `category_id`, `file`, `description`, `download_count`, `is_public`.
- Validasi mime: `pdf, doc, docx, xls, xlsx, ppt, pptx, zip, rar`. Max 20 MB.
- Frontend: list dengan filter kategori, tombol unduh menambah counter.

### 5.5 Facility
- Field: `name`, `slug`, `image`, `icon` (opsional), `description`, `order`, `is_active`.
- Frontend: section di Home + halaman daftar fasilitas.

### 5.6 Academic
- Field: `name`, `slug`, `image`, `head_name`, `description` (rich), `curriculum`, `order`, `is_active`.
- Frontend: halaman akademik (program/kurikulum).

### 5.7 Profile (Singleton Page)
- Halaman tunggal berisi: sejarah, visi, misi, sambutan kepala sekolah, struktur organisasi (text/image), logo.
- Implementasi: Filament **Settings page / single-record resource**.

### 5.8 Contact
- **Backend**: kelola info kontak global (alamat, telp, email, jam operasional, peta embed, sosial media) via Settings.
- **Pesan masuk**: tabel `contact_messages` dari form publik (`name`, `email`, `phone`, `subject`, `message`, `created_at`, `is_read`).
- **Frontend**: halaman Kontak + form (rate-limited).
- **Notifikasi**: email ke admin saat ada pesan baru.

### 5.9 SPMB (Sistem Penerimaan Murid Baru)
**Tujuan**: pendaftaran calon siswa baru secara online.

#### 5.9.1 Periode SPMB (`spmb_periods`)
- Field: `name` (mis. "SPMB 2026/2027"), `start_date`, `end_date`, `quota`, `fee`, `description`, `is_active`.
- Hanya satu periode aktif pada satu waktu.

#### 5.9.2 Pendaftaran (`spmb_registrations`)
- Field calon siswa:
  - **Data diri**: `registration_number` (auto), `full_name`, `nick_name`, `gender`, `birth_place`, `birth_date`, `nik`, `nisn`, `religion`, `address`, `phone`, `email`.
  - **Data orang tua**: `father_name`, `father_job`, `father_phone`, `mother_name`, `mother_job`, `mother_phone`, `guardian_name` (opsional).
  - **Asal sekolah**: `previous_school`, `graduation_year`, `npsn`.
  - **Status**: `pending → verifying → accepted | rejected | waiting_list`.
  - **Catatan admin**: `admin_note`.
  - **Periode**: `spmb_period_id`.
- Field dokumen (`spmb_documents`):
  - Tipe: `kk`, `akta`, `foto`, `ijazah`, `raport`, `lainnya`.
  - File pdf/jpg/png, max 5 MB per file.

#### 5.9.3 Alur
1. Pengunjung membuka halaman SPMB → melihat info periode aktif.
2. Mengisi form bertahap (Wizard Livewire) + upload dokumen.
3. Submit → menerima **nomor pendaftaran** + email konfirmasi.
4. Pendaftar dapat **cek status** dengan memasukkan nomor pendaftaran + tanggal lahir.
5. Admin (Filament) memverifikasi data & dokumen → mengubah status → email otomatis terkirim ke pendaftar.
6. Admin dapat **export Excel/CSV** dan **cetak bukti pendaftaran (PDF)**.

#### 5.9.4 Notifikasi Email
- Saat submit (konfirmasi + nomor pendaftaran).
- Saat status berubah (accepted/rejected/waiting_list).
- Reminder dokumen kurang (opsional).

### 5.10 Setting (Global)
- Identitas: `school_name`, `logo`, `favicon`, `tagline`.
- Kontak global: `address`, `phone`, `email`, `whatsapp`, `maps_embed`.
- Sosial media: `facebook`, `instagram`, `youtube`, `tiktok`.
- SEO default: `meta_title`, `meta_description`, `og_image`.
- Footer: `footer_text`, `copyright`.
- Implementasi: `spatie/laravel-settings` atau Filament native settings page (cached).

---

## 6. Modul Pendukung

### 6.1 User Management
- CRUD user, assign role, reset password, aktif/non-aktif.
- 2FA (opsional, fase lanjut).

### 6.2 Activity Log
- Library: `spatie/laravel-activitylog`.
- Catat: create/update/delete pada model utama (News, SPMB, Settings, User).
- Halaman log di Filament (filter by user, model, tanggal).

### 6.3 Dashboard Filament
Widget:
- Total berita (published/draft).
- Total pendaftar SPMB periode aktif (per status).
- Pesan kontak belum dibaca.
- Grafik pendaftar SPMB 30 hari terakhir.
- 5 berita terpopuler.

---

## 7. Halaman Frontend (Livewire)

| Route | Komponen | Konten |
|---|---|---|
| `/` | `Home` | Hero slider, sambutan singkat, fasilitas unggulan, berita terbaru, CTA SPMB |
| `/profil` | `Profile` | Sejarah, visi/misi, sambutan, struktur |
| `/akademik` | `AcademicIndex` | Daftar program/kurikulum |
| `/akademik/{slug}` | `AcademicShow` | Detail program |
| `/fasilitas` | `FacilityIndex` | Daftar fasilitas |
| `/galeri` | `GalleryIndex` | Daftar album |
| `/galeri/{slug}` | `GalleryShow` | Detail album + lightbox |
| `/berita` | `NewsIndex` | List berita + filter kategori/tag + search |
| `/berita/{slug}` | `NewsShow` | Detail berita + related |
| `/download` | `DownloadIndex` | List file unduhan + filter kategori |
| `/kontak` | `Contact` | Info kontak + form pesan |
| `/spmb` | `SpmbLanding` | Info periode + tombol daftar + cek status |
| `/spmb/daftar` | `SpmbRegister` | Wizard form pendaftaran |
| `/spmb/status` | `SpmbStatus` | Form input nomor + tanggal lahir → tampil status |

---

## 8. Skema Database (ringkas)

```
users(id, name, email, password, is_active, ...)
roles, permissions, model_has_roles, model_has_permissions, role_has_permissions  // spatie

settings(group, key, value, ...)  // spatie/laravel-settings

sliders(id, title, subtitle, image, link_url, link_text, order, is_active)

news_categories(id, name, slug)
tags(id, name, slug)
news(id, title, slug, category_id, excerpt, body, thumbnail, author_id, published_at, status, views, meta_title, meta_description, deleted_at)
news_tag(news_id, tag_id)

galleries(id, title, slug, cover, description, published_at)
gallery_items(id, gallery_id, image, caption, order)

download_categories(id, name, slug)
downloads(id, title, slug, category_id, file, description, download_count, is_public)

facilities(id, name, slug, image, icon, description, order, is_active)
academics(id, name, slug, image, head_name, description, curriculum, order, is_active)

contact_messages(id, name, email, phone, subject, message, is_read, created_at)

spmb_periods(id, name, start_date, end_date, quota, fee, description, is_active)
spmb_registrations(id, spmb_period_id, registration_number, full_name, ..., status, admin_note, deleted_at)
spmb_documents(id, spmb_registration_id, type, file_path)

activity_log(...)  // spatie/activitylog
```

---

## 9. Non-Functional Requirements

- **Responsive** mobile-first (Tailwind).
- **SEO**: slug unik, meta tag, sitemap.xml (opsional fase lanjut), Open Graph.
- **Performance**: lazy loading image, image optimization (WebP), cache settings/menu.
- **Security**:
  - CSRF di semua form.
  - Validasi server-side ketat.
  - Sanitasi rich-text editor output.
  - Rate limit form publik (Contact: 5/menit, SPMB: 3/menit per IP).
  - Whitelist mime + max size pada setiap upload.
  - Hash password bcrypt; force HTTPS di produksi.
- **Accessibility**: alt text wajib pada upload gambar.
- **Backup**: dump DB harian (cron / `spatie/laravel-backup` opsional).
- **Audit**: activity log untuk perubahan penting.

---

## 10. Roadmap & Milestone

| Milestone | Cakupan | Estimasi |
|---|---|---|
| **M1 — Foundation** | Setup Laravel 12, Filament 4, auth, role, settings, profile singleton | Minggu 1 |
| **M2 — Konten** | Slider, Berita, Gallery, Download, Facility, Academic | Minggu 2–3 |
| **M3 — Frontend** | Theme Tailwind + komponen Livewire untuk semua halaman publik | Minggu 3–4 |
| **M4 — SPMB** | Periode, form pendaftaran, dokumen, status, notifikasi email, export, cetak PDF | Minggu 5 |
| **M5 — Polishing** | Activity log, dashboard, optimisasi, deployment, dokumentasi | Minggu 6 |

---

## 11. Acceptance Criteria (ringkas per fitur)

- ✅ Admin dapat login di `/admin` dengan akun yang dibuat seeder.
- ✅ Setiap modul konten memiliki CRUD dengan validasi & policy role.
- ✅ Frontend menampilkan data dinamis dari DB sesuai status `published`.
- ✅ Form Contact menyimpan ke DB **dan** mengirim email ke admin.
- ✅ SPMB:
  - Pendaftar mendapat `registration_number` unik & email konfirmasi.
  - Status berubah → email pemberitahuan terkirim.
  - Admin dapat export CSV pendaftar.
  - Pendaftar dapat cek status via halaman publik.
- ✅ Setting global mempengaruhi seluruh tampilan frontend (logo, kontak, sosmed, footer).
- ✅ Activity log mencatat perubahan model utama.
- ✅ Lulus PageSpeed mobile ≥ 80.

---

## 12. Risiko & Mitigasi

| Risiko | Mitigasi |
|---|---|
| Upload dokumen besar saat SPMB ramai | Batas ukuran file, queue, storage terpisah |
| Email gagal terkirim | Queue + retry + log; fallback notifikasi in-app |
| Operator salah publish berita | Status draft + role contributor terbatas |
| Data SPMB sensitif bocor | Akses berbasis role, HTTPS, soft delete, audit log |

---

## 13. Out of Scope (fase awal)
- Mobile app native.
- Pembayaran online SPMB (gateway). Pembayaran tetap manual transfer di fase 1.
- E-learning / LMS.
- Multi-bahasa (i18n) — dapat ditambah di fase berikutnya.
