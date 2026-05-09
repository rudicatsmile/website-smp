
# Rekomendasi Fitur Tambahan untuk Website Sekolah

Berdasarkan fitur yang sudah ada (Berita, Galeri, Prestasi, Akademik, SPMB, Kalender, Download), berikut rekomendasi fitur baru yang akan memberi nilai tambah bagi guru, siswa, dan orang tua.

## 👨‍🏫 Untuk Guru

- **Direktori Guru & Profil Publik** OK
  Halaman list guru dengan foto, mata pelajaran, latar belakang pendidikan, dan sertifikasi. Membangun kepercayaan publik dan transparansi tenaga pendidik.

- **Bank Materi & Modul Ajar** OK
  Repository upload modul ajar/RPP per mata pelajaran & kelas, bisa dibagikan publik atau khusus internal. Mendukung dokumentasi Kurikulum Merdeka.

      Implementasi fitur Bank Materi & Modul Ajar selesai.

      Ringkasan
        1. DB: 2 tabel — material_categories & materials (FK ke staff_members sebagai penulis).
        2. Models: MaterialCategory & Material dengan relasi, scopes (active, public, published, featured), accessors (file_url, cover_url, file_size_human, type_label, grade_label, curriculum_label).
        3. Seeders: 8 mata pelajaran + 6 sample materi (mix tipe/grade/public) + entry PageHero materi.
        4. Filament Admin: 2 resource lengkap di /admin/material-categories & /admin/materials dengan upload file (PDF/DOC/PPT/ZIP, max 20MB), cover image, RichEditor, TagsInput, dan filter (mapel, tipe, kelas, kurikulum, publik, unggulan).
        5. Public: Livewire MaterialIndex (/materi) — search + 4 filter + featured strip + card grid + pagination dengan URL queryString. MaterialShow (/materi/{slug}) — hero, badges metadata, deskripsi RichText, sidebar download (counter), author card, materi terkait.
        6. Download: MaterialDownloadController (/materi/{slug}/download) — auto-increment download_count, hanya melayani materi is_active && is_public && published.
        7. Akses Internal: materi is_public=false 404 di publik, hanya akses via Filament admin (sesuai MVP).
        8. Navbar: link Materi ditambahkan di skin education & milleneal (desktop + mobile).
        9. Verifikasi: 9 route terdaftar, migrasi & seeder berjalan tanpa error.
        
        Akses via http://127.0.0.1:8000/materi atau panel admin di /admin.

- **Jadwal Mengajar & Piket**
  Kalender khusus untuk guru menampilkan jadwal mengajar, piket, dan rapat. Integrasi dengan kalender utama yang sudah ada.

        Implementasi fitur Jadwal Mengajar & Piket selesai.

        Ringkasan
        1. DB: tabel staff_schedules (FK ke staff_members + material_categories), tipe: mengajar/piket/rapat/lainnya, kolom day_of_week, start_time/end_time, kelas, lokasi, periode efektif (effective_from/until, academic_year, semester).
        2. Model: StaffSchedule dengan konstanta DAYS/TYPES/TYPE_COLORS, scopes (active, forDay, byType, inEffect), accessors (day_label, type_label, time_range, display_title). StaffMember mendapat relasi schedules() ordered by hari & jam.
        3. Seeder: 16 sample entri (mengajar Senin–Jumat, piket Sabtu, rapat Senin pagi) lintas guru & mapel + entry PageHero jadwal.
        4. Filament: StaffScheduleResource di /admin/staff-schedules dengan form 4 section (Detail/Waktu/Periode Berlaku/Tampilan), select mata pelajaran reaktif (visible saat type=mengajar), dan filter (tipe/hari/guru/tahun ajaran/aktif). Kategori rapat-guru ditambahkan ke SchoolEventForm untuk event one-off.
        5. Public /jadwal: Livewire StaffScheduleIndex dengan filter (search/hari/tipe/guru), toggle Grid view (7 kolom hari × stack item dengan time-range, badge tipe, guru, lokasi) dan List view (per hari). URL queryString sinkron.
        6. Profil Guru /guru/{slug}: section "Jadwal Mingguan" dikelompokkan per hari, dengan link "Lihat Jadwal Lengkap" yang prefilter ke guru bersangkutan.
        7. /kalender: ditambah CTA banner ke /jadwal di atas filter, plus tombol filter kategori "Rapat Guru" baru.
        8. Navbar: link Jadwal di kedua skin (education + milleneal, desktop + mobile).
        9. Verifikasi: migrasi sukses, 16 schedule rows tersimpan, route jadwal.index terdaftar.
        
        
        Akses via http://127.0.0.1:8000/jadwal, profil guru, kalender, atau /admin.

- **Pengumuman Internal Guru**
  Dashboard berita khusus internal (rapat, surat tugas, agenda dinas).


      Implementasi fitur Pengumuman Internal Guru selesai.

        Ringkasan
        1. DB: 4 migrasi baru — notifications (Filament bell), staff_members.user_id (link ke User), internal_announcements, internal_announcement_acknowledgements (pivot, FK names disingkat agar muat batas 64 char MySQL).

        2. Models:
        InternalAnnouncement dengan konstanta CATEGORIES/PRIORITIES/PRIORITY_COLORS/TARGET_ROLES, scopes (active, published, pinned, forUser), accessors (category_label, priority_label, priority_color), isAcknowledgedBy(User).
        InternalAnnouncementAcknowledgement (pivot dengan timestamps).
        User mendapat staffMember(), announcementAcknowledgements(), dan role teacher ditambahkan ke canAccessPanel.
        StaffMember mendapat relasi user().

        3. Role: teacher ditambahkan ke RoleSeeder (5 role total).

        4. Filament Admin (/admin/internal-announcements):
          - Form 4 section: Konten (RichEditor body, kategori, prioritas), Target (CheckboxList target_roles, multi-select target_staff_ids), Lampiran (FileUpload multiple, max 10MB, pdf/doc/xls/img), Pengaturan (pinned/active/published_at/expires_at).
          - Table dengan badge priority (color-coded), pinned icon, ack count, filter category/priority/pinned/active.
          - Action "Tandai Dibaca" muncul jika user belum acknowledge; otomatis tersembunyi setelah klik.
          - Custom ViewPage menampilkan badges, body RichText, lampiran (download), info target & ack count untuk admin.
          - Permission scoping: teacher hanya melihat pengumuman yang ditargetkan + published + active; tidak bisa create/edit/delete.
          - NavigationBadge menampilkan jumlah pengumuman belum-dibaca untuk role teacher.

        5. Notifikasi Filament Database: Observer InternalAnnouncementObserver mengirim notification ke recipients saat pengumuman dipublish (resolve target by role + specific staff_ids). databaseNotifications() diaktifkan di AdminPanelProvider. Bell icon akan muncul di topbar Filament.

        6. Seeder: 5 sample (rapat pinned/penting, surat tugas urgent + target staff specific, agenda dinas, umum, expired test) + demo user teacher@smpalwahoniyah9.sch.id / password linked ke staff member pertama.

        7. Verifikasi: 4 route Filament terdaftar, 5 announcements + 1 teacher user tersimpan, semua cache clear.


        Cara Test
        1. Login admin (admin@smpalwahoniyah9.sch.id / password) → menu Pengumuman Internal → lihat semua + CRUD penuh.

        2. Login teacher (teacher@smpalwahoniyah9.sch.id / password) → menu sama tapi read-only + tombol "Tandai Dibaca" + bell icon notification.

        3. Buat pengumuman baru sebagai admin → notifikasi otomatis terkirim ke teacher target.

## 🎓 Untuk Siswa

- **Portal Tugas & Pengumuman Kelas**
  Login siswa untuk melihat tugas, deadline, dan pengumuman per kelas. Bisa download materi guru.

      Implementasi selesai. Berikut ringkasan:

      Yang Dibuat :

      Database (6 tabel)

        - school_classes, students, class_assignments, class_announcements, 
          assignment_submissions, class_materials

      Models

        - SchoolClass, Student, ClassAssignment, ClassAnnouncement, 
          AssignmentSubmission, ClassMaterial
        - User ditambah relasi student()
        - Role baru student (RoleSeeder)

      Filament Admin (panel guru/admin) — group Akademik:

        - SchoolClassResource (admin only)
        - StudentResource (admin only) — dgn aksi Generate Akun
        - ClassAssignmentResource + SubmissionsRelationManager untuk grading
        - ClassAnnouncementResource
        - ClassMaterialResource
        
      Portal Siswa Livewire (di /portal):

        - /portal/login — halaman login khusus
        - /portal — dashboard (stats: tugas aktif/terlambat/dikumpulkan + list)
        - /portal/tugas & /portal/tugas/{slug} — list + detail + upload submission
        - /portal/pengumuman & /portal/pengumuman/{slug}
        - /portal/materi
        - /portal/profil (ganti password)

      Layout portal.blade.php dgn nav top (Dashboard, Tugas, Pengumuman, Materi)
      Auth Flow

      - bootstrap/app.php — guest di /portal* redirect ke portal.login, lainnya ke admin login
      - Login component validasi role student (admin diizinkan preview)
      
      Seeded Data (verified): 6 kelas, 21 siswa, 8 tugas, 4 pengumuman, 5 materi.

      Cara Test :
      
      - Admin: admin@smpalwahoniyah9.sch.id / password → http://127.0.0.1:8000/admin → menu Akademik (Kelas, Siswa, Tugas Kelas, Pengumuman Kelas, Materi Kelas).

      - Guru: teacher@smpalwahoniyah9.sch.id / password → buat tugas, lihat submission siswa, beri nilai via Relation Manager.

      - Siswa demo: siswa@smpalwahoniyah9.sch.id / password → http://127.0.0.1:8000/portal/login → dashboard, kerjakan tugas, upload submission.
      
      - Generate akun siswa lain via tabel Siswa di Filament (tombol Generate Akun) — email format {nis}@siswa.smpalwahoniyah9.sch.id, password default siswa123.


- **E-Library / Perpustakaan Digital**
  Koleksi e-book, jurnal, dan referensi yang bisa diunduh atau dibaca online. Bisa dikategorikan per mata pelajaran.

- **Bank Soal & Latihan**
  Latihan soal interaktif (quiz) per mapel, terutama menjelang UTS/UAS/ujian. Auto-scoring untuk feedback langsung.

- **Konseling Online (BK Digital)**
  Form pengaduan/konsultasi anonim ke guru BK terkait masalah belajar atau pribadi. Penting untuk kesehatan mental siswa.

- **Profil Alumni & Tracer Study**
  Halaman alumni sukses + form tracer study. Memotivasi siswa & data evaluasi sekolah.

- **Ekstrakurikuler Online**
  Pendaftaran ekskul digital, jadwal latihan, prestasi tim, galeri kegiatan per ekskul.

## 👨‍👩‍👧 Untuk Orang Tua

- **Portal Orang Tua (Login)**
  Dashboard orang tua untuk melihat:
  - Nilai rapor dan perkembangan akademik anak
  - Absensi harian (terintegrasi dengan sistem presensi)
  - Pelanggaran/poin disiplin
  - Pembayaran SPP & tagihan sekolah

- **Notifikasi WhatsApp/Email Otomatis**
  Notifikasi otomatis ke orang tua untuk: ketidakhadiran anak, pengumuman penting, jadwal pengambilan rapor, tagihan jatuh tempo.

- **Pembayaran Online (Payment Gateway)**
  Bayar SPP/biaya sekolah via Midtrans/Xendit (VA, QRIS, e-wallet). Riwayat transaksi otomatis.

- **Surat Izin Online**
  Form pengajuan izin tidak masuk sekolah dengan upload bukti (surat dokter, dll). Tercatat otomatis di sistem absensi.

- **Buku Penghubung Digital**
  Komunikasi 2 arah orang tua ↔ wali kelas terkait perkembangan anak.

## 🌐 Fitur Umum (Semua User)

- **Chatbot / FAQ Interaktif**
  Bot AI untuk menjawab pertanyaan umum (PPDB, biaya, fasilitas) 24/7.

- **Virtual Tour 360°**
  Tur sekolah virtual dengan foto 360° untuk calon siswa & orang tua yang tidak bisa datang langsung.

- **Live Streaming Event**
  Streaming upacara, wisuda, atau pentas seni untuk orang tua yang tidak hadir.

- **Multi-bahasa (ID/EN/Arab)**
  Penting jika sekolah berbasis Islam atau punya program internasional.

- **PWA (Progressive Web App)**
  Bisa diinstall di HP seperti aplikasi native, push notification, akses offline untuk konten penting.

- **Aksesibilitas (a11y)**
  Mode kontras tinggi, pembaca layar, ukuran font adjustable untuk inklusi siswa berkebutuhan khusus.

## 🎯 Rekomendasi Prioritas

Jika harus pilih top 3 yang paling berdampak dan realistis dikerjakan:

1. **Portal Orang Tua + Notifikasi WA** — Engagement orang tua naik drastis, value paling terasa
2. **Pembayaran SPP Online** — Solve masalah operasional besar, mudah diukur ROI-nya
3. **Direktori Guru + Bank Materi** — Quick win, manfaatkan struktur konten yang sudah ada

Mau saya implementasikan salah satu fitur di atas? Atau ada yang ingin didiskusikan lebih detail?