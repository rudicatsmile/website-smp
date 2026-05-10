*{{ $school_name }}*
Informasi Absensi Siswa

Yth. Bapak/Ibu *{{ $parent_name ?? 'Orang Tua/Wali' }}*,

Kami informasikan bahwa putra/putri Anda:
Nama    : *{{ $student_name }}*
NIS     : {{ $nis }}
Kelas   : {{ $class_name ?? '-' }}
Tanggal : {{ $date }}

Tercatat berstatus: *{{ $status_label }}*
@if(! empty($note))

Catatan: {{ $note }}
@endif

Mohon perhatian dan bimbingan Bapak/Ibu di rumah.

Terima kasih.
— {{ $school_name }}
