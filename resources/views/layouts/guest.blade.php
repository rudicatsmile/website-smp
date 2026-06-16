<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $settings = app(\App\Settings\GeneralSettings::class); @endphp
    <title>{{ $title ?? 'E-Voting OSIS' }} &middot; {{ $settings->school_name }}</title>
    @if($settings->favicon)
        <link rel="icon" href="{{ asset('storage/'.$settings->favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans text-slate-800 antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
