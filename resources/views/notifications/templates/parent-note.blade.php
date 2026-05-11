@php
    $school = config('notifications.school_name');
    $isToTeacher = ($recipient_type ?? 'parent') === 'teacher';
@endphp
*{{ $school }}*
*Buku Penghubung Digital*

@if($isToTeacher)
Yth. {{ $recipient_name ?: 'Bapak/Ibu Wali Kelas' }},

Orang tua siswa *{{ $student_name }}*{{ !empty($class_name) ? ' ('.$class_name.')' : '' }} mengirim pesan baru pada topik berikut:
@else
Yth. {{ $recipient_name ?: 'Bapak/Ibu' }},

Wali kelas {{ !empty($class_name) ? '*'.$class_name.'* ' : '' }}mengirim pesan baru terkait *{{ $student_name }}* pada topik berikut:
@endif

📌 *Topik   :* {{ $subject }}
🏷 *Kategori:* {{ $category_label }}
🆔 *Kode    :* {{ $code }}

💬 Pesan:
{{ \Illuminate\Support\Str::limit($body, 400) }}

@if(!empty($url))
Buka percakapan: {{ $url }}
@endif
