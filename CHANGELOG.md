# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan di dalam file ini.
Format berdasarkan [Keep a Changelog](https://keepachangelog.com/id-ID/1.0.0/), dan proyek ini menganut [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Fitur **Ganti Password Global** yang dapat diakses oleh semua jenis pengguna (Siswa, Orang Tua, dan Guru).
- Komponen antarmuka (UI) kustom untuk Ganti Password dengan desain modern yang bersih dan *student-centric*.
- Halaman **Profil** baru pada Portal Orang Tua.
- Halaman **Profil** baru pada Mobile PWA (Portal Guru).
- Mengaktifkan fitur **Profil Bawaan (Built-in Profile)** pada admin panel Filament agar pengguna dengan role *teacher* (dan role lain) dapat mengganti password mereka langsung dari dalam CMS.
- Fitur **Cetak Jurnal Bulanan (PDF)** pada daftar Sesi Mengajar untuk merangkum seluruh kegiatan guru, pencapaian, tugas, dan kendala selama satu bulan.
- Kolom **Wacana / Tema** pada pengisian Daftar Topik Pembelajaran yang dilengkapi dengan fitur *Table Grouping* secara otomatis.
- Fitur **Duplikasi (Replicate)** pada Rencana Pembelajaran dengan *pop-up form* konfirmasi untuk mengubah data Kelas/Mapel tujuan duplikasi.
- Menambahkan **Penomoran (No)** dan fitur **Hapus Massal (Bulk Delete)** pada Daftar Topik Rencana Pembelajaran.

### Changed
- Memindahkan logika "Ganti Password" di Portal Siswa menjadi komponen global terpusat agar dapat digunakan di seluruh portal yang ada.
- Mengubah hak akses fitur **Duplikasi (Replicate)** pada Rencana Pembelajaran agar dapat digunakan oleh role `teacher` (sebelumnya hanya `super_admin`).
- Form konfigurasi **Apply ke Tanggal** diubah menjadi menggunakan komponen `Repeater` agar guru dapat mengatur "Jam Mulai" dan "Jam Selesai" secara dinamis dan spesifik untuk setiap hari yang berbeda.
- Mengubah logika distribusi Sesi Mengajar di `CurriculumPlanService` menjadi **1-to-1 Mapping** berurutan tanpa bergantung pada minggu kalender.

### Deprecated
### Removed
### Fixed
- Memperbaiki kegagalan fitur "Cetak Jurnal Bulanan" (Error: `Call to undefined relationship [materialCategory]`) dengan menggunakan relasi `subject` yang benar pada model `LessonSession`.
- Memperbaiki kegagalan *update state* secara real-time pada *field* "Tugas / PR" dan "Kendala" saat form "Selesai dan Catat" di-*submit* (dengan memperbarui array `$this->refreshFormData()`).
- Mengatasi *SQL Error: Integrity constraint violation (Duplicate entry)* saat melakukan duplikasi Rencana Pembelajaran.
- Memperbaiki pesan *error* "Unknown column 'topics_count'" saat duplikasi Rencana Pembelajaran dengan menggunakan `excludeAttributes()`.
- Memperbaiki pesan *error* "Class Filament\Tables\Actions\EditAction not found" dengan menggunakan *namespace* Filament v3/v4 yang benar.
- Memperbaiki kegagalan validasi "Guru tidak sesuai dengan jadwal sesi ini" saat tombol "Mulai Mengajar" ditekan di server production dengan melakukan *type-casting* ke `(int)` pada *foreign key* `staff_member_id`.
- Memperbaiki *bug* dimana status absensi gagal tersimpan secara diam-diam (popup menutup tapi data tidak berubah) akibat perbedaan tipe data *strict comparison* pada ID Kelas di `AbsensiHariIni`.
- Mengatasi masalah kolom status UI yang tidak langsung berubah (harus *refresh*) saat fitur "Sisa Hadir" atau "Sisa Alpa" diklik dengan merombak *query* ke database guna menghindari isu *cache* pada fitur bawaan Livewire.

### Security
