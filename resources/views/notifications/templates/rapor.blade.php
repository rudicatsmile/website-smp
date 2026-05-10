*{{ $school_name }}*
Jadwal Pengambilan Rapor

Yth. Bapak/Ibu *{{ $parent_name ?? 'Orang Tua/Wali' }}*,

Kami informasikan jadwal pengambilan rapor putra/putri Anda:
Nama  : *{{ $student_name }}*
Kelas : {{ $class_name ?? '-' }}

Hari/Tanggal : *{{ $date }}*
Waktu        : {{ $time }}
Tempat       : {{ $place }}
@if(! empty($notes))

Catatan: {{ $notes }}
@endif

Mohon hadir tepat waktu. Terima kasih.

— {{ $school_name }}
