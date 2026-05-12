@php
    $school = config('notifications.school_name');
@endphp
*{{ $school }}*
*Reminder Mengajar*

Yth. {{ $recipient_name ?: 'Bapak/Ibu Guru' }},

Anda memiliki jadwal mengajar:

📚 *Mapel    :* {{ $subject_name }}
🏫 *Kelas    :* {{ $class_name }}
⏰ *Jam      :* {{ $time_range }}
📌 *Topik    :* {{ $topic }}

@if(!empty($period))
Periode: {{ $period }}
@endif

@if(!empty($url))
Buka sesi: {{ $url }}
@endif

_Selamat mengajar! 📖_
