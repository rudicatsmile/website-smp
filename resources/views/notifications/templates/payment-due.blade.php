*{{ $school_name }}*
Pengingat Tagihan Pembayaran

Yth. Bapak/Ibu *{{ $parent_name ?? 'Orang Tua/Wali' }}*,

Kami informasikan tagihan atas nama:
Nama    : *{{ $student_name }}*
NIS     : {{ $nis }}
Kelas   : {{ $class_name ?? '-' }}

Rincian tagihan:
Jenis       : {{ $type_label }}
Periode     : {{ $period }}
Jumlah      : *{{ $amount_formatted }}*
Jatuh tempo : {{ $due_date }}

@if($is_overdue)
*Status: JATUH TEMPO ({{ $days_overdue }} hari)*
Mohon segera melakukan pembayaran.
@elseif($days_to_due === 0)
*Status: Jatuh tempo HARI INI*
@else
Sisa waktu : {{ $days_to_due }} hari lagi
@endif

Terima kasih atas perhatian dan kerjasama Bapak/Ibu.

— {{ $school_name }}
