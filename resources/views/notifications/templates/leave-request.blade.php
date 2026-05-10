@php
    $school = config('notifications.school_name');
    $isApproved = ($status ?? null) === 'approved';
    $isRejected = ($status ?? null) === 'rejected';
@endphp
*{{ $school }}*

Yth. {{ $parent_name ?: 'Bapak/Ibu' }},

Pengajuan izin tidak masuk sekolah untuk *{{ $student_name }}*{{ !empty($class_name) ? ' ('.$class_name.')' : '' }} berstatus: *{{ $status_label }}*.

Detail pengajuan:
- Jenis     : {{ $type_label }}
- Tanggal   : {{ $date_range }}{{ !empty($day_count) ? ' ('.$day_count.' hari)' : '' }}
- Alasan    : {{ $reason }}
- No. Tiket : #{{ $request_id }}

@if($isApproved)
✅ Izin telah disetujui dan otomatis tercatat pada absensi siswa untuk tanggal di atas.
@elseif($isRejected)
❌ Mohon maaf, pengajuan izin tidak dapat disetujui. Silakan hubungi sekolah untuk informasi lebih lanjut.
@else
ℹ️ Pengajuan sedang ditinjau oleh sekolah.
@endif

@if(!empty($review_note))

Catatan dari sekolah:
{{ $review_note }}
@endif

Terima kasih atas perhatian dan kerja samanya.

Hormat kami,
{{ $school }}
