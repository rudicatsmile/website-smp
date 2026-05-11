# Development Rules
## CMS SMP Al Wathoniyah 9 — Laravel 12 + Filament 4 + Livewire 3

> Dokumen ini berisi aturan, konvensi, dan workflow yang **wajib** diikuti seluruh kontributor proyek. Tujuannya menjaga konsistensi, keamanan, dan maintainability.

---

## 1. Tech Stack (Wajib)

| Komponen | Versi |
|---|---|
| PHP | 8.2+ (rekomendasi 8.3) |
| Laravel | 12.x |
| Filament | 4.x |
| Livewire | 3.x |
| TailwindCSS | 4.x |
| Alpine.js | bundled with Livewire |
| Database | MariaDB 10.6+ / MySQL 8 |
| Node | 20 LTS+ |
| Composer | 2.7+ |

### Paket Wajib
- `filament/filament:^4.0`
- `livewire/livewire:^3.0`
- `spatie/laravel-permission`
- `spatie/laravel-activitylog`
- `spatie/laravel-settings`
- `spatie/laravel-medialibrary` (opsional)
- `barryvdh/laravel-dompdf` (cetak bukti SPMB)
- `maatwebsite/excel` (export pendaftar)
- `laravel/pint` (dev)
- `pestphp/pest` (dev)

---

## 2. Project Structure

```
app/
├── Filament/
│   ├── Resources/        # CRUD admin (NewsResource, SliderResource, ...)
│   ├── Pages/            # Custom pages (Settings, Profile singleton)
│   └── Widgets/          # Dashboard widgets
├── Livewire/
│   ├── Pages/            # Full-page components (Home, NewsIndex, ...)
│   ├── Forms/            # Form components (ContactForm, SpmbRegisterWizard)
│   └── Components/       # Shared UI components
├── Models/               # Eloquent models
├── Policies/             # Authorization policies
├── Notifications/        # Mail/database notifications
├── Mail/                 # Mailable classes
├── Actions/              # Single-purpose action classes
├── Services/             # Domain services
├── Settings/             # spatie/laravel-settings classes
└── Enums/                # PHP enums (StatusEnum, RoleEnum, dll)

resources/
├── views/
│   ├── layouts/app.blade.php
│   ├── livewire/
│   ├── components/       # Blade components
│   └── emails/
├── css/app.css
└── js/app.js

database/
├── migrations/
├── seeders/              # RoleSeeder, AdminUserSeeder, SettingSeeder, DemoSeeder
└── factories/

routes/
├── web.php               # Frontend routes
└── auth.php
```

---

## 3. Konvensi Penamaan

| Item | Konvensi | Contoh |
|---|---|---|
| Model | Singular PascalCase | `News`, `SpmbRegistration` |
| Tabel | snake_case plural | `news`, `spmb_registrations` |
| Migration | `YYYY_MM_DD_HHMMSS_create_xxx_table.php` | default Laravel |
| Controller | suffix `Controller` | `ContactController` |
| Filament Resource | suffix `Resource` | `NewsResource` |
| Livewire Component | PascalCase | `Pages\NewsIndex` |
| Route name | `kebab-case.action` | `news.show`, `spmb.register` |
| Variable/method | camelCase | `publishedAt()` |
| Const & Enum case | UPPER_SNAKE / PascalCase enum | `Status::Published` |
| Blade view | kebab-case | `news-card.blade.php` |
| File upload folder | `storage/app/public/{module}/{yyyy}/{mm}/` | `public/news/2026/05/...` |

Slug: gunakan `Str::slug()` + uniqueness check (`spatie/laravel-sluggable` opsional).

---

## 4. Coding Standards

- **Wajib lulus** `vendor/bin/pint` sebelum commit.
- PSR-12 + Laravel default.
- Gunakan `declare(strict_types=1);` pada Action/Service class.
- Type hint **semua** parameter & return type.
- **Hindari** logic berat di Controller/Filament Resource — pindahkan ke `Action` atau `Service`.
- Eloquent relationship harus eksplisit return type:
  ```php
  public function category(): BelongsTo { ... }
  ```
- Gunakan **Form Request** untuk validasi non-Filament.
- Gunakan **Enum** untuk status (`draft|published|archived`, dll), jangan string magic.
- Hindari N+1: gunakan `with()` / `loadMissing()`.

---

## 5. Database Rules

- **Selalu** lewat migration; dilarang edit DB manual.
- Setiap foreign key wajib `onDelete()` eksplisit (`cascade` atau `restrict`).
- Wajib **index** pada kolom: `slug`, `status`, `published_at`, `is_active`, kolom foreign key.
- Soft delete pada model penting: `News`, `SpmbRegistration`, `User`.
- Timestamps wajib (`created_at`, `updated_at`).
- Gunakan `unsignedBigInteger` / `foreignId()->constrained()`.
- Seeder wajib idempotent (`firstOrCreate`, `updateOrCreate`).
- Dilarang menyimpan password/secret pada seeder produksi — gunakan `.env`.

---

## 6. Filament Rules

- Satu **Resource per model utama**.
- Form panjang dipecah ke `Section` / `Tabs`.
- Wajib **Policy** untuk setiap Resource (`canViewAny`, `create`, `update`, `delete`).
- Bulk action minimum: `delete`, `export` (jika relevan).
- Field upload: gunakan `FileUpload`/`SpatieMediaLibraryFileUpload` dengan:
  - `disk('public')`
  - `directory('{module}/'.now()->format('Y/m'))`
  - `maxSize` & `acceptedFileTypes` eksplisit
  - `image()->imageEditor()` untuk gambar
- Filter tabel wajib untuk kolom: status, kategori, periode (di SPMB).
- Gunakan Filament `Notifications` untuk feedback aksi.
- Settings page menggunakan `spatie/laravel-settings` + Filament page custom.

---

## 7. Livewire Rules

- **Full-page component** untuk halaman publik:
  ```php
  #[Layout('layouts.app')]
  #[Title('Berita - SMP Al Wathoniyah 9')]
  class NewsIndex extends Component { ... }
  ```
- Pagination: gunakan tema Tailwind, `WithPagination`.
- Search: `#[Url]` + `updatingSearch()` reset page + debounce 300ms (`wire:model.live.debounce.300ms`).
- Tampilkan **loading state** (`wire:loading`) dan skeleton pada list.
- Form: gunakan **Livewire Form Object** (`Livewire\Form`) untuk SPMB & Contact.
- Validasi: `rules()` method + `messages()` ID.
- Setelah submit sukses: dispatch event + redirect/flash.

---

## 8. Security

- CSRF aktif di semua form (default Laravel).
- Sanitasi output rich text (gunakan editor yang menghasilkan HTML aman, mis. **Tiptap** dari Filament).
- **Rate limit**:
  - `contact.store`: `throttle:5,1`
  - `spmb.register`: `throttle:3,1`
  - login admin: `throttle:5,1`
- **Upload whitelist**:
  - Image: `jpg, jpeg, png, webp` (max 2 MB)
  - Document: `pdf, doc, docx, xls, xlsx, ppt, pptx` (max 20 MB)
  - SPMB doc: `pdf, jpg, jpeg, png` (max 5 MB)
- Hash password: bcrypt (default).
- Wajib HTTPS di produksi (`APP_URL=https://`, `FORCE_HTTPS=true`).
- Disable directory listing pada storage publik.
- Jangan log data sensitif (NIK, password) ke file log.

---

## 9. SEO & Performance

- Slug unik auto-generate, immutable setelah publish (kecuali oleh admin).
- Meta `title` & `description` per artikel & halaman setting.
- Open Graph image otomatis pakai `thumbnail` atau `og_image` dari setting.
- **Cache**:
  - Settings global: `Cache::rememberForever('settings.global', ...)` di-flush saat update.
  - Menu/sidebar publik: cache 1 jam dengan tag.
- Image: konversi ke WebP via Intervention/Spatie, max-width 1600px untuk hero, 800px thumbnail.
- Eager load pada list (`with(['category', 'author'])`).
- Pagination wajib pada list (default 12/halaman frontend).

---

## 10. Git Workflow

- Branch:
  - `main` → produksi (protected)
  - `develop` → integrasi
  - `feature/<scope>` → fitur baru
  - `fix/<scope>` → perbaikan bug
- **Conventional Commits**:
  - `feat: tambahkan modul SPMB`
  - `fix: koreksi validasi upload KK`
  - `chore: update composer`
  - `refactor:`, `docs:`, `test:`, `style:`
- PR wajib:
  1. Lulus `vendor/bin/pint --test`
  2. Lulus `php artisan migrate:fresh --seed` di lokal
  3. Lulus `php artisan test`
  4. Direview minimal 1 reviewer

---

## 11. Environment

`.env` wajib berisi (sesuai spesifikasi):
```
APP_NAME="CMS SMP Al Wathoniyah 9"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost
APP_LOCALE=id
APP_TIMEZONE=Asia/Jakarta

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_smp_v3
DB_USERNAME=root
DB_PASSWORD=

QUEUE_CONNECTION=database
MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@smpalwathoniyah9.sch.id"
MAIL_FROM_NAME="${APP_NAME}"

FILESYSTEM_DISK=public
```

`MAIL_*` **wajib** diisi sebelum SPMB go-live.

---

## 12. Testing

- Framework: **Pest** (atau PHPUnit).
- Minimum coverage feature test:
  - Login admin Filament.
  - Submit Contact form → DB + email.
  - Submit SPMB form → record + dokumen tersimpan + email konfirmasi.
  - Cek status SPMB via nomor pendaftaran.
  - Berita: hanya status `published` yang tampil di frontend.
  - Authorization: contributor tidak bisa publish.
- Jalankan: `php artisan test` di CI sebelum merge.

---

## 13. Deployment Checklist

1. `composer install --no-dev --optimize-autoloader`
2. `php artisan key:generate` (jika fresh)
3. `php artisan migrate --force`
4. `php artisan db:seed --class=ProductionSeeder --force`
5. `php artisan storage:link`
6. `npm ci && npm run build`
7. `php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan event:cache`
8. `php artisan filament:optimize`
9. Setup **queue worker** (supervisor): `php artisan queue:work --queue=default,mail`
10. Setup **scheduler** cron: `* * * * * php artisan schedule:run`
11. Backup DB harian (cron `mysqldump` atau `spatie/laravel-backup`).
12. Pastikan `APP_DEBUG=false`, `APP_ENV=production`.
13. SSL aktif & redirect HTTP → HTTPS.

---

## 14. Aturan Tambahan

- **Jangan commit** `.env`, `storage/*`, `vendor/`, `node_modules/`.
- **Jangan hardcode** kredensial / API key — selalu via `.env`.
- **Komentar** tidak ditambahkan secara berlebihan; kode harus self-explanatory.
- **Dokumen** PRD & RULES ini harus diperbarui saat ada perubahan ruang lingkup.
- Setiap modul baru: tambahkan **seeder demo** + **factory** + **feature test**.
