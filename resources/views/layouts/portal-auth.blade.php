<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $settings = app(\App\Settings\GeneralSettings::class); @endphp
    <title>{{ $title ?? 'Login Siswa' }} &middot; {{ $settings->school_name }}</title>
    @if($settings->favicon)
        <link rel="icon" href="{{ asset('storage/'.$settings->favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-500 via-teal-500 to-sky-600 flex items-center justify-center p-4">
    {{ $slot }}
    @livewireScripts
</body>
</html>
