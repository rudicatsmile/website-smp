Sistem Penilaian — Selesai ✅
Database (4 tabel baru)
Tabel	                    Fungsi
--------------------------------------------------------------- 
session_assessments	Header  kuis/ulangan per sesi mengajar
session_assessment_scores	Nilai per siswa per assessment
exam_sessions	            Ujian khusus (UTS/UAS/PTS/PAS/Remedial)
exam_scores	                Nilai per siswa per ujian


Alur Nilai Harian:
-----------------
1. Buka /admin/lesson-sessions/{id}/edit → tab Penilaian
2. Klik "Buat Penilaian" — jika belum absensi, muncul warning otomatis
3. Klik "Input Nilai" → /admin/input-nilai-sesi?assessment=X
4. Siswa hadir bisa diisi nilai, tidak hadir input dinonaktifkan (abu-abu)


Alur Nilai Ujian Khusus:
-----------------------
1. Menu /admin/exam-sessions → buat sesi ujian (UTS/UAS/dll)
2. Klik "Input Nilai" → /admin/input-nilai-ujian?exam=X
3. Semua siswa kelas tampil + status absensi hari itu (jika ada) + kolom Remedial


Agregasi ke grades :
------------------
Di /admin/grades → pilih beberapa record → Actions → Hitung Otomatis
Formula: nilai_akhir = 40% tugas + 30% UTS + 30% UAS, predikat dihitung otomatis

Di /admin/grades sekarang ada tombol "Buat Ledger Nilai" (hijau) di header.

Cara pakai:

1. Klik Buat Ledger Nilai → pilih Kelas, Mata Pelajaran, Guru, Tahun Ajaran, Semester
2. Submit → record kosong dibuat untuk semua siswa aktif di kelas itu
3. Record yang sudah ada tidak ditimpa (dilewati + dihitung di notifikasi)
4. Setelah ledger terisi, gunakan "Hitung Otomatis" (bulk action) untuk mengisi nilai dari data kuis/ulangan/ujian