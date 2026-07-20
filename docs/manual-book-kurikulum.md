# 📘 Buku Panduan Guru: Manajemen Kurikulum & Jurnal Mengajar

Halo Bapak/Ibu Guru! 👋 

Buku panduan ini dirancang khusus untuk mempermudah Bapak/Ibu dalam mengelola administrasi mengajar sehari-hari. Mulai dari merencanakan materi di awal semester, menerapkannya ke dalam jadwal otomatis, hingga mencetak jurnal mengajar bulanan—semuanya bisa dilakukan dengan alur yang terintegrasi.

Berikut adalah langkah-langkah praktis beserta **Pro Tips** untuk menghemat waktu Anda!

---

## 1. Persiapan Awal: Mengelola Data Master

Sebelum menyusun rencana, pastikan Bapak/Ibu (atau Admin Sekolah) telah memasukkan referensi data master berikut pada menu Master/Referensi Kurikulum:
- **Tujuan Pembelajaran**: Target kompetensi yang harus dicapai siswa, spesifik per mata pelajaran.
- **Model Pembelajaran**: Kerangka konseptual yang digunakan sebagai pedoman di kelas (contoh: *Project Based Learning*, *Discovery Learning*).
- **Metode Pembelajaran**: Cara atau teknik interaksi belajar mengajar (contoh: Ceramah, Diskusi Kelompok, Tanya Jawab).
- **Jenis Asesmen**: Kategori penilaian yang akan diterapkan (contoh: Formatif, Sumatif, Penugasan).
- **Media Pembelajaran**: Alat bantu yang digunakan (contoh: Proyektor, Modul Interaktif, Video).

> [!TIP]
> **Pro Tip dari Developer:**
> Masukkan data master ini selengkap mungkin di awal. Data yang Bapak/Ibu buat di sini akan otomatis muncul sebagai opsi *dropdown* (pilihan) saat menyusun Rencana Pembelajaran, sehingga mempercepat kerja Bapak/Ibu ke depannya!

---

## 2. Membuat Rencana Pembelajaran (Global)

Langkah pertama di awal semester adalah membuat cetak biru (blueprint) rencana mengajar untuk satu kelas dan mata pelajaran.

**Cara Penggunaan:**
1. Masuk ke menu **Kurikulum** > **Rencana Pembelajaran**.
2. Klik tombol **"Buat Rencana Pembelajaran"**.
3. Isi informasi utama:
   - Pilih **Kelas** dan **Tahun Ajaran**.
   - Pilih **Mata Pelajaran**. 
     > ⚠️ **Penting:** Pilihan Mata Pelajaran ini akan secara dinamis menyaring dan memunculkan opsi yang spesifik pada kolom **Guru Pengampu**, **Tujuan Pembelajaran**, **Model Pembelajaran**, **Metode Pembelajaran**, dan **Media Pembelajaran**. Pastikan Anda memilih Mata Pelajaran terlebih dahulu!
   - Masukkan **Topik Utama** dan **Alokasi Waktu**.
   - Pilih **Guru Pengampu**, **Tujuan Pembelajaran**, **Model**, **Metode**, dan **Media** yang akan digunakan secara umum.
4. Klik **Simpan**.

> [!TIP]
> **Pro Tip dari Developer:**
> Gunakan fitur **"Duplikasi"**! Jika Bapak/Ibu mengajar mata pelajaran yang sama di beberapa kelas paralel (misalnya Kelas 7A dan 7B), Bapak/Ibu cukup membuat Rencana Pembelajaran satu kali di kelas 7A, lalu klik tombol **Duplikasi**, dan ubah kelasnya menjadi 7B. Semua topik akan otomatis ikut tersalin!

*[Placeholder: Screenshot Halaman List & Form Rencana Pembelajaran]*

---

## 3. Menyusun Rencana Pembelajaran Per Pertemuan

Setelah Rencana Pembelajaran global dibuat, langkah selanjutnya adalah memecahnya menjadi pertemuan-pertemuan mingguan.

**Cara Penggunaan:**
1. Buka **Rencana Pembelajaran** yang sudah Bapak/Ibu buat.
2. Scroll ke bawah menuju bagian **"Daftar Topik"**.
3. Klik tombol **"Rencana Pembelajaran Per Pertemuan"**.
4. Isi detail pertemuan:
   - Tentukan **Minggu Ke-** dan **Pertemuan Ke-**.
   - Masukkan **Wacana/Tema** dan **Topik/Bab**.
   - Tambahkan Alur Tujuan Pembelajaran (ATP) beserta Level KKO-nya.
5. Simpan topik tersebut. Ulangi untuk pertemuan-pertemuan berikutnya.

> [!TIP]
> **Pro Tip dari Developer:**
> Bapak/Ibu tidak perlu mengingat atau mengetik ulang Tujuan Pembelajaran & Metode. Sistem akan secara cerdas memunculkan opsi-opsi yang sudah Bapak/Ibu pilih di langkah pertama tadi. Fokuslah pada penyusunan ATP (Alur Tujuan Pembelajaran).

*[Placeholder: Screenshot Tabel Daftar Topik & Form Input Topik]*

---

## 4. 🗓 Apply ke Tanggal (Generate Jadwal Otomatis)

Ini adalah fitur paling ajaib di sistem ini! Daripada membuat jadwal secara manual satu per satu setiap harinya, Bapak/Ibu bisa men-generate seluruh sesi mengajar selama satu semester/bulan hanya dengan satu klik.

**Cara Penggunaan:**
1. Di bagian **"Daftar Topik"**, klik tombol hijau bertuliskan **"🗓 Apply ke Tanggal"**.
2. Masukkan **Tanggal Mulai** dan **Tanggal Selesai** (misal: 1 Juli - 31 Desember).
3. Atur **Jadwal Mengajar**:
   - Pilih hari aktif (misal: Senin).
   - Masukkan **Jam Mulai** dan **Jam Selesai**.
   - (Bapak/Ibu bisa klik "Tambah" jika dalam seminggu mengajar di hari yang berbeda, misal Senin dan Rabu).
4. Biarkan opsi **"Lewati hari libur"** menyala agar sistem otomatis meloncati tanggal merah/libur nasional.
5. Klik **Submit**. Voila! Semua topik Bapak/Ibu tadi sudah berubah menjadi Sesi Mengajar yang siap dieksekusi.

> [!TIP]
> **Pro Tip dari Developer:**
> Hati-hati dengan opsi **"Langsung publish"**. Jika dimatikan (default), sesi akan dibuat berstatus *Draft*, memberi Bapak/Ibu kesempatan untuk merevisi. Jika Bapak/Ibu sudah sangat yakin dengan jadwalnya, centang agar statusnya langsung *Published*.

*[Placeholder: Screenshot Modal "Apply ke Tanggal" dengan input jadwal]*

---

## 5. Sesi Mengajar

Menu ini adalah tempat utama Bapak/Ibu mengelola setiap pertemuan kelas yang sudah dijadwalkan.

**1. Memahami Status pada Daftar Sesi Mengajar:**
Pada daftar sesi mengajar, terdapat kolom status dengan arti berikut:
- **Draft**: Sesi masih berupa rancangan (belum bisa dimulai).
- **Published**: Sesi sudah diterbitkan dan siap untuk dimulai.
- **In Progress**: Sesi sedang berlangsung saat ini.
- **Completed**: Sesi sudah selesai dilaksanakan.
- **Cancelled**: Sesi dibatalkan.

**2. Mengelola Formulir Sesi Mengajar:**
Saat Bapak/Ibu membuka detail (formulir) sebuah sesi mengajar, terdapat beberapa elemen penting:
- **Hubungan 'Status' dan 'Pencapaian (%)'**: Status sesi sangat berkaitan dengan persentase pencapaian. Sesi yang telah tuntas dilaksanakan (Completed) harus disertai dengan input seberapa besar target materi tercapai (Pencapaian %).
- **Elemen Catatan Khusus**:
  - **Materi**: Pokok bahasan yang diajarkan pada sesi tersebut.
  - **Tugas**: Pekerjaan rumah atau tugas yang diberikan ke siswa.
  - **Assessment**: Penilaian yang dilakukan selama sesi belajar.
  - **Kasus Peserta Didik**: Catatan khusus mengenai perilaku, pelanggaran, atau kejadian spesifik murid di kelas.
- **Fungsi Tombol di Halaman Sesi**:
  - **Cetak Sesi**: Untuk mengunduh detail sesi ini ke dalam dokumen cetak/PDF.
  - **Log Aktifitas**: Untuk melihat riwayat perubahan data pada sesi ini (siapa yang mengubah dan kapan).
- **Alur Pelaksanaan Kelas**:
  - Jika sesi berstatus **Published**, akan muncul tombol **"Mulai Mengajar"**. Bila tombol ini diklik, status kelas akan aktif dan sesi tersebut akan langsung tampil di *card* **Berlangsung** pada halaman "Mengajar Hari Ini".
  - Setelah tombol **"Mulai Mengajar"** diklik, tombol tersebut akan berubah menjadi **"Selesai & Catat"**.
  - Saat jam pelajaran usai, klik tombol **"Selesai & Catat"**. Sebuah *popup* akan muncul untuk meminta input realisasi:
    - **Pencapaian (%)*** (Wajib diisi)
    - **Catatan Kegiatan**
    - **Tugas / PR**
    - **Kendala**

> [!TIP]
> **Pro Tip dari Developer:**
> Biasakan untuk langsung mengklik "Selesai & Catat" di akhir pelajaran selagi ingatan masih segar. Jurnal bulanan Bapak/Ibu **HANYA** akan mencetak sesi yang statusnya sudah Selesai (Completed).

*[Placeholder: Screenshot Halaman List & Form Sesi Mengajar]*

---

## 6. Mengajar Hari Ini

Fitur ini berfungsi sebagai asisten pribadi yang merangkum jadwal Bapak/Ibu khusus untuk hari ini, sehingga Bapak/Ibu tidak perlu mencari jadwal di daftar yang panjang.

**Cara Penggunaan:**
1. Masuk ke halaman **Dashboard** atau menu khusus **Mengajar Hari Ini**.
2. Sistem akan memfilter secara cerdas jadwal mengajar Bapak/Ibu di hari tersebut. 
3. Pantau *card* **Berlangsung** untuk melihat kelas mana yang saat ini sedang Bapak/Ibu ajar (sesi yang tombol "Mulai Mengajar"-nya sudah diklik).

*[Placeholder: Screenshot Widget Mengajar Hari Ini dan Card Berlangsung]*

---

## 7. Cetak Jurnal Mengajar Bulanan

Akhir bulan telah tiba! Waktunya menyerahkan laporan/jurnal mengajar kepada Kepala Sekolah atau bagian kurikulum.

**Cara Penggunaan:**
1. Buka menu **Sesi Mengajar**.
2. Di pojok kanan atas, klik tombol hijau **"Cetak Jurnal Bulanan"**.
3. Pilih **Bulan** dan **Tahun** yang ingin dicetak.
4. Klik **Submit**, dan dokumen PDF dengan format Jurnal Mengajar resmi (Lanskap/Landscape) akan otomatis terunduh ke perangkat Bapak/Ibu.

> [!IMPORTANT]
> **Catatan Penting:**
> Jika muncul notifikasi _"Tidak ada data jurnal yang sudah selesai di bulan ini"_, silakan periksa kembali daftar Sesi Mengajar Bapak/Ibu. Pastikan semua sesi di bulan tersebut sudah diubah statusnya menjadi **"Completed"** (dengan mengklik "Selesai & Catat").

*[Placeholder: Screenshot Tombol Cetak Jurnal & Hasil PDF Jurnal]*

---

## 💡 Pesan Penutup & Best Practices dari Developer

Bapak/Ibu Guru yang luar biasa, aplikasi ini dirancang untuk **mengurangi** beban administrasi, bukan menambahnya. Berikut adalah ringkasan kebiasaan terbaik (Best Practices) untuk alur kerja yang mulus:

1. **Investasi Waktu di Awal Semester:** Buat semua _Rencana Pembelajaran_ dan _Topik_ secara lengkap di minggu pertama. Gunakan fitur **Duplikasi** antar kelas, lalu tembak jadwalnya dengan **Apply ke Tanggal**.
2. **Review Mingguan:** Sistem mungkin melewatkan libur dadakan yang belum masuk sistem. Lakukan pengecekan singkat setiap hari Jumat/Senin untuk memastikan jadwal minggu ini akurat.
3. **Disiplin Status 'Completed':** Jangan menunda mengubah status sesi menjadi "Selesai" hingga akhir bulan. Lakukan langsung setelah kelas berakhir selagi ingatan Bapak/Ibu masih segar tentang kejadian di kelas hari itu.

Selamat mengajar dan semoga sistem ini mempermudah keseharian Bapak/Ibu! 🚀
