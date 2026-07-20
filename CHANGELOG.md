# Changelog

Semua perubahan penting pada proyek ini akan didokumentasikan di dalam file ini.
Format berdasarkan [Keep a Changelog](https://keepachangelog.com/id-ID/1.0.0/), dan proyek ini menganut [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Fitur **Cetak Jurnal Bulanan (PDF)** pada daftar Sesi Mengajar untuk merangkum seluruh kegiatan guru, pencapaian, tugas, dan kendala selama satu bulan.
- Kolom **Wacana / Tema** pada pengisian Daftar Topik Pembelajaran yang dilengkapi dengan fitur *Table Grouping* secara otomatis.
- Fitur **Duplikasi (Replicate)** pada Rencana Pembelajaran dengan *pop-up form* konfirmasi untuk mengubah data Kelas/Mapel tujuan duplikasi.
- Menambahkan **Penomoran (No)** dan fitur **Hapus Massal (Bulk Delete)** pada Daftar Topik Rencana Pembelajaran.

### Changed
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

### Security
