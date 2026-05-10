*{{ $school_name }}*
Pengumuman{{ ! empty($class_name) ? ' — Kelas ' . $class_name : '' }}

*{{ $title }}*

{{ $body_plain }}
@if(! empty($published_at))

Terbit: {{ $published_at }}
@endif
@if(! empty($url))

Selengkapnya: {{ $url }}
@endif

— {{ $school_name }}
