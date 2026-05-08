<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php($settings = app(\App\Settings\GeneralSettings::class))
    <title>{{ $title ?? $settings->school_name }}</title>
    <meta name="description" content="{{ $description ?? $settings->meta_description }}">
    @if($settings->favicon)
        <link rel="icon" href="{{ asset('storage/'.$settings->favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <x-site.navbar :settings="$settings" :transparent="request()->routeIs('home')" />

    <main class="min-h-[60vh] {{ request()->routeIs('home') ? '' : 'pt-16' }}">
        {{ $slot }}
    </main>

    <x-site.footer :settings="$settings" />

    <x-site.popup />

    @livewireScripts
    @stack('scripts')
</body>
</html>
