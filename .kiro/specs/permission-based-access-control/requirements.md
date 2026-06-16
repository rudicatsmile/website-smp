# Requirements Document

## Introduction

Dokumen ini mendefinisikan kebutuhan untuk migrasi sistem kontrol akses panel admin dari pendekatan berbasis ROLE (hardcoded melalui method `canAccess()` yang memanggil `hasAnyRole()`) menjadi pendekatan MURNI berbasis PERMISSION menggunakan Filament Shield (bezhansalleh/filament-shield 4.2) di atas Spatie Laravel Permission 7.4.

Tujuan akhir: setiap akses ke Resource, Page, dan Widget pada panel admin (`/admin`) dikontrol sepenuhnya oleh permission yang di-assign ke role melalui halaman Shield Role Management (`/admin/shield/roles/{id}/edit`), bukan oleh logika role hardcoded yang tersebar di banyak file.

Karena perubahan ini berdampak luas (56 Resource, 11 Page, 41 penggunaan `canAccess()`, sejumlah Widget) dan berisiko mengunci akses user produksi, migrasi WAJIB dipecah menjadi kebutuhan-kebutuhan yang dapat diimplementasikan dan diuji secara bertahap (incremental, per-task). Setiap tahap harus menjaga agar akses yang sedang berjalan tidak rusak.

Panel kedua (Tahfidz `/tahfidz`), portal siswa (`/portal`), dan portal orang tua (`/portal/ortu`) berada DI LUAR scope dan harus tetap berfungsi seperti sekarang.

## Glossary

- **Admin_Panel**: Panel Filament dengan id `admin` dan path `/admin`, didefinisikan di `app/Providers/Filament/AdminPanelProvider.php`. Satu-satunya panel yang menjadi target migrasi ini.
- **Shield**: Plugin bezhansalleh/filament-shield 4.2 yang sudah terdaftar pada Admin_Panel dan menyediakan generasi policy/permission serta halaman Shield Role Management.
- **Shield_Generator**: Perintah artisan `php artisan shield:generate --all` yang menghasilkan Policy dan Permission untuk Resource, Page, dan Widget pada Admin_Panel.
- **Policy**: Class Laravel di `app/Policies` yang dihasilkan Shield untuk memetakan permission ke aksi otorisasi (viewAny, view, create, update, delete, dll).
- **Permission**: Entitas Spatie Permission (mis. `View:Quiz`, `Update:Quiz`) yang menentukan hak akses atas sebuah Resource/Page/Widget pada Admin_Panel.
- **Role**: Entitas Spatie Permission. Role yang sudah ada: `super_admin`, `admin`, `teacher`, `counselor`, `editor`, `piket`, `guru_ekstrakurikuler`, `panel_user`, `student`, `parent`.
- **Super_Admin**: Role `super_admin` yang melalui konfigurasi Shield (`super_admin.enabled = true`, `intercept_gate = before`) mendapat akses penuh tanpa perlu permission eksplisit (gate bypass).
- **Permission_Assignment**: Proses pemberian (assign) satu atau lebih Permission ke sebuah Role, baik melalui seeder maupun halaman Shield Role Management.
- **Permission_Seeder**: Komponen seeder Laravel yang menetapkan baseline Permission_Assignment untuk Role yang sudah ada agar tidak ada user yang terkunci pasca migrasi.
- **Manual_Access_Method**: Method statis `canAccess()` (dan turunannya `shouldRegisterNavigation()`, `canView()`) yang saat ini ditulis manual pada Resource/Page/Widget dan memanggil `hasAnyRole()`, sehingga menimpa logika otorisasi Shield.
- **Ekskul_Hiding_Trait**: Trait `app/Filament/Concerns/HidesFromEkskulRole.php` yang meng-override `canViewAny()` untuk menyembunyikan menu dari role `guru_ekstrakurikuler`.
- **Data_Scoping**: Pembatasan record yang ditampilkan berdasarkan kepemilikan/penugasan guru, diimplementasikan melalui `getEloquentQuery()` pada Resource tertentu (mis. QuizResource, QuestionBankResource, LessonSessionResource, CurriculumPlanResource). Data_Scoping BUKAN bagian dari permission Shield dan harus tetap dipertahankan.
- **Out_Of_Scope_Surfaces**: Panel Tahfidz (`/tahfidz`), portal siswa (`/portal`), dan portal orang tua (`/portal/ortu`) yang tidak menggunakan Shield dan tidak boleh terpengaruh oleh migrasi ini.

## Requirements

### Requirement 1: Generasi Policy dan Permission Shield

**User Story:** Sebagai developer, saya ingin men-generate Policy dan Permission untuk seluruh Resource, Page, dan Widget pada Admin_Panel, sehingga seluruh permukaan panel memiliki dasar otorisasi berbasis permission.

#### Acceptance Criteria

1. WHEN Shield_Generator dijalankan dengan opsi `--all`, THE Shield SHALL menghasilkan Policy untuk setiap Resource pada Admin_Panel ke dalam direktori `app/Policies`.
2. WHEN Shield_Generator dijalankan dengan opsi `--all`, THE Shield SHALL menghasilkan Permission untuk setiap Resource, Page, dan Widget pada Admin_Panel yang tidak terdaftar pada daftar exclude konfigurasi.
3. WHERE sebuah entitas terdaftar pada daftar exclude konfigurasi Shield (mis. Dashboard, AccountWidget, FilamentInfoWidget), THE Shield SHALL melewati entitas tersebut tanpa membuat Permission.
4. WHEN proses generasi selesai, THE Shield SHALL menyimpan setiap Permission yang dihasilkan ke dalam tabel permission Spatie dengan format penamaan sesuai konfigurasi (case `pascal`, separator `:`).
5. IF direktori `app/Policies` belum ada saat Shield_Generator dijalankan, THEN THE Shield SHALL membuat direktori tersebut sebelum menulis file Policy.
6. IF proses generasi mengalami kegagalan pada sebagian entitas, THEN THE Shield SHALL menyimpan Permission yang berhasil dibuat dan mencatat (log) entitas yang gagal tanpa membatalkan seluruh proses.

### Requirement 2: Mempertahankan Akses Penuh Super Admin

**User Story:** Sebagai administrator sistem, saya ingin role `super_admin` tetap memiliki akses penuh sepanjang dan sesudah migrasi, sehingga selalu ada jalur akses administratif yang tidak terkunci.

#### Acceptance Criteria

1. THE Admin_Panel SHALL memberikan akses ke seluruh Resource, Page, dan Widget kepada Super_Admin tanpa memerlukan Permission_Assignment eksplisit.
2. WHILE konfigurasi Shield menetapkan `super_admin.intercept_gate` bernilai `before`, THE Admin_Panel SHALL meloloskan setiap pengecekan otorisasi untuk Super_Admin melalui gate intercept.
3. WHEN seluruh tahap migrasi telah selesai, THE Admin_Panel SHALL mengizinkan hanya Super_Admin untuk mengakses halaman Shield Role Management pada `/admin/shield/roles/{id}/edit`.

### Requirement 3: Seeding Baseline Permission untuk Role yang Sudah Ada

**User Story:** Sebagai administrator sistem, saya ingin Permission baseline di-assign ulang ke role yang sudah ada setelah generasi, sehingga tidak ada user yang tiba-tiba kehilangan akses pasca migrasi.

#### Acceptance Criteria

1. WHEN Permission_Seeder dijalankan, THE Permission_Seeder SHALL meng-assign kepada setiap Role yang sudah ada (selain Super_Admin) sekumpulan Permission yang setara dengan akses yang dimiliki Role tersebut sebelum migrasi.
2. THE Permission_Seeder SHALL menetapkan Permission_Assignment secara deklaratif sehingga pemetaan Role-ke-Permission terdokumentasi di dalam kode.
3. WHEN Permission_Seeder dijalankan ulang pada database yang sudah memiliki Permission_Assignment hasil seeder sebelumnya, THE Permission_Seeder SHALL mengganti seluruh Permission_Assignment yang dikelolanya dengan kumpulan yang dideklarasikan sehingga hasil akhir identik tanpa duplikasi (idempoten melalui penggantian penuh).
4. IF sebuah Permission yang direferensikan oleh Permission_Seeder belum ada di database, THEN THE Permission_Seeder SHALL berhenti seketika pada Permission pertama yang tidak ditemukan dan melaporkan nama Permission tersebut.
5. WHEN Permission_Seeder selesai dijalankan, THE Permission_Seeder SHALL mempertahankan Permission_Assignment yang sudah ada pada Role di luar daftar yang dikelolanya.

### Requirement 4: Menghapus Manual Access Method pada Resource

**User Story:** Sebagai developer, saya ingin menghapus atau menyesuaikan `canAccess()` manual pada Resource, sehingga otorisasi tidak lagi di-hardcode dan sepenuhnya didelegasikan ke Shield.

#### Acceptance Criteria

1. WHEN migrasi sebuah Resource dilakukan, THE Admin_Panel SHALL tidak lagi memuat Manual_Access_Method berbasis `hasAnyRole()` pada Resource tersebut.
2. WHEN sebuah Resource telah dimigrasi, THE Admin_Panel SHALL menentukan visibilitas dan akses Resource tersebut berdasarkan Permission yang di-assign ke Role pengguna melalui Policy hasil Shield.
3. WHILE proses migrasi berlangsung per Resource, THE Admin_Panel SHALL tetap menampilkan Resource yang belum dimigrasi sesuai perilaku lamanya tanpa error.
4. IF seorang pengguna tidak memiliki Permission `viewAny` untuk sebuah Resource yang telah dimigrasi, THEN THE Admin_Panel SHALL menyembunyikan Resource tersebut dari navigasi dan menolak akses langsung ke URL-nya.

### Requirement 5: Menghapus Manual Access Method pada Custom Page

**User Story:** Sebagai developer, saya ingin custom Page dikontrol oleh Permission `view` masing-masing, sehingga registrasi navigasi dan akses Page mengikuti permission.

#### Acceptance Criteria

1. WHEN migrasi sebuah custom Page dilakukan, THE Admin_Panel SHALL tidak lagi memuat Manual_Access_Method berbasis `hasAnyRole()` pada Page tersebut.
2. WHEN sebuah custom Page telah dimigrasi, THE Admin_Panel SHALL menentukan visibilitas navigasi dan akses Page tersebut berdasarkan Permission `view` Page yang di-assign ke Role pengguna.
3. IF seorang pengguna tidak memiliki Permission `view` untuk sebuah Page yang telah dimigrasi, THEN THE Admin_Panel SHALL menolak akses ke Page tersebut dengan respons HTTP 403 dan tidak mengembalikan respons HTTP 200 apa pun.
4. IF seorang pengguna tidak memiliki Permission `view` untuk sebuah Page yang telah dimigrasi, THEN THE Admin_Panel SHALL menyembunyikan Page tersebut dari navigasi.

### Requirement 6: Migrasi Widget Berbasis Permission

**User Story:** Sebagai developer, saya ingin visibilitas Widget dikontrol oleh Permission, sehingga `canView()` berbasis role tidak lagi diperlukan.

#### Acceptance Criteria

1. WHEN migrasi sebuah Widget dilakukan, THE Admin_Panel SHALL tidak lagi memuat Manual_Access_Method berbasis `hasAnyRole()` pada Widget tersebut.
2. WHEN sebuah Widget telah dimigrasi, THE Admin_Panel SHALL menentukan visibilitas Widget tersebut berdasarkan Permission `view` Widget yang di-assign ke Role pengguna.
3. IF seorang pengguna tidak memiliki Permission `view` untuk sebuah Widget yang telah dimigrasi, THEN THE Admin_Panel SHALL tidak merender konten Widget tersebut, sementara Widget tetap diperbolehkan muncul pada layout/menu sebagai placeholder atau dalam keadaan nonaktif.

### Requirement 7: Penanganan Perilaku Penyembunyian Menu untuk Guru Ekstrakurikuler

**User Story:** Sebagai administrator sistem, saya ingin perilaku penyembunyian menu untuk role `guru_ekstrakurikuler` tetap konsisten setelah migrasi, sehingga role tersebut hanya melihat menu yang sesuai.

#### Acceptance Criteria

1. WHEN migrasi penanganan Ekskul_Hiding_Trait dilakukan, THE Admin_Panel SHALL mengontrol visibilitas Resource yang sebelumnya disembunyikan dari role `guru_ekstrakurikuler` melalui ketiadaan Permission yang relevan pada Role tersebut.
2. THE Permission_Seeder SHALL tidak meng-assign Permission untuk Resource yang sebelumnya disembunyikan dari `guru_ekstrakurikuler` kepada Role `guru_ekstrakurikuler`.
3. WHERE Ekskul_Hiding_Trait masih terpasang pada sebuah Resource setelah migrasi, THE Admin_Panel SHALL tetap mengizinkan perilaku penyembunyian berbasis trait tersebut tetap berlaku tanpa error.
4. THE Admin_Panel SHALL mempertahankan perilaku penyembunyian lama melalui Ekskul_Hiding_Trait pada Resource yang masih menggunakannya, terlepas dari status migrasi Resource tersebut.

### Requirement 8: Mempertahankan Data Scoping per Guru

**User Story:** Sebagai guru, saya ingin tetap hanya melihat record milik/penugasan saya pada Resource yang ber-scope, sehingga pembatasan data per-guru tidak hilang akibat migrasi permission.

#### Acceptance Criteria

1. WHEN sebuah Resource ber-scope telah selesai dimigrasi ke kontrol berbasis Permission, THE Admin_Panel SHALL mempertahankan logika Data_Scoping pada `getEloquentQuery()` untuk Resource tersebut.
2. WHILE seorang pengguna dengan Role `teacher` (dan bukan `super_admin` maupun `admin`) mengakses Resource yang ber-scope, THE Admin_Panel SHALL membatasi record yang ditampilkan hanya pada record yang terkait dengan guru tersebut.
3. WHEN sebuah Resource ber-scope dimigrasi ke kontrol berbasis Permission, THE Admin_Panel SHALL tetap menerapkan Data_Scoping secara independen dari pengecekan Permission.

### Requirement 9: Menjaga Permukaan di Luar Scope Tetap Berfungsi

**User Story:** Sebagai pengguna panel Tahfidz, portal siswa, dan portal orang tua, saya ingin akses saya tidak terpengaruh oleh migrasi Admin_Panel, sehingga layanan yang saya gunakan tetap berjalan.

#### Acceptance Criteria

1. THE migrasi ini SHALL tidak mengubah mekanisme kontrol akses pada Out_Of_Scope_Surfaces.
2. WHEN tahap manapun dari migrasi Admin_Panel selesai dijalankan, THE Out_Of_Scope_Surfaces SHALL tetap dapat diakses oleh pengguna yang berwenang sesuai perilaku sebelum migrasi.
3. THE Shield_Generator SHALL tidak menghasilkan Permission untuk entitas yang hanya terdapat pada panel Tahfidz selama konfigurasi discovery dibatasi pada panel default.

### Requirement 10: Eksekusi Migrasi Secara Bertahap

**User Story:** Sebagai developer, saya ingin migrasi dipecah menjadi langkah-langkah yang dapat dijalankan dan diuji satu per satu, sehingga risiko terhadap akses produksi diminimalkan.

#### Acceptance Criteria

1. THE migrasi ini SHALL disusun menjadi sejumlah tahap yang masing-masing dapat dijalankan secara independen dan menghasilkan kondisi panel yang tetap dapat digunakan.
2. WHEN sebuah tahap migrasi selesai, THE Admin_Panel SHALL tetap dapat diakses oleh Super_Admin dan oleh Role yang permission-nya telah di-assign.
3. WHILE sebagian Resource, Page, atau Widget telah dimigrasi dan sebagian belum, THE Admin_Panel SHALL beroperasi tanpa kesalahan otorisasi pada entitas yang belum dimigrasi.
4. IF terjadi kesalahan otorisasi selama migrasi bertahap, THEN THE Admin_Panel SHALL menyampaikan pesan yang membedakan masalah akibat migrasi yang belum tuntas dari masalah permission yang sebenarnya.
5. WHEN sebuah tahap migrasi diselesaikan, THE proses pengembangan SHALL menyediakan cara memverifikasi hasil tahap tersebut sebelum melanjutkan ke tahap berikutnya.

### Requirement 11: Verifikasi Akses per Role

**User Story:** Sebagai administrator sistem, saya ingin memverifikasi bahwa setiap Role hanya dapat mengakses entitas sesuai permission-nya, sehingga hasil migrasi terbukti benar.

#### Acceptance Criteria

1. WHEN seorang pengguna dengan Role tertentu mengakses sebuah Resource, Page, atau Widget yang telah dimigrasi, THE Admin_Panel SHALL mengizinkan akses jika dan hanya jika Role tersebut memiliki Permission yang sesuai.
2. WHEN seorang pengguna tanpa Permission yang sesuai mencoba mengakses URL entitas yang telah dimigrasi secara langsung, THE Admin_Panel SHALL menolak akses dengan respons HTTP 403.
3. THE proses verifikasi SHALL berlaku untuk seluruh pengguna, dengan cakupan pengujian minimal mencakup Role `admin`, `teacher`, `counselor`, `editor`, `piket`, dan `guru_ekstrakurikuler`.
4. WHEN verifikasi dilakukan untuk Super_Admin, THE Admin_Panel SHALL mengizinkan akses ke seluruh entitas yang telah dimigrasi.

### Requirement 12: Prosedur Penerapan ke Produksi

**User Story:** Sebagai developer yang melakukan deployment, saya ingin prosedur penerapan ke produksi yang jelas dan berurutan, sehingga migrasi diterapkan tanpa mengunci pengguna produksi.

#### Acceptance Criteria

1. THE prosedur deployment SHALL mendefinisikan urutan langkah: menjalankan Shield_Generator, menjalankan Permission_Seeder, melakukan Permission_Assignment ke Role, lalu membersihkan cache permission dan cache aplikasi.
2. WHEN langkah pembersihan cache permission dijalankan setelah Permission_Assignment, THE Admin_Panel SHALL menggunakan Permission_Assignment terbaru pada permintaan berikutnya.
3. IF langkah Permission_Seeder belum dijalankan di produksi, THEN THE prosedur deployment SHALL menahan langkah penghapusan Manual_Access_Method agar tidak menyebabkan Role kehilangan akses.
4. THE prosedur deployment SHALL menyatakan langkah verifikasi pasca-deployment untuk memastikan Super_Admin dan minimal satu Role non-super dapat mengakses Admin_Panel sesuai permission.
