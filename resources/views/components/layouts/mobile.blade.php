<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="theme-color" content="#ffffff">
        <title>{{ $title ?? 'Mobile Dashboard' }}</title>

        <!-- Tailwind CSS & Livewire Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-gray-100 antialiased text-gray-900 flex justify-center min-h-screen">
        
        <!-- Mobile Wrapper (Max Width for desktop viewing) -->
        <div class="w-full max-w-md bg-white min-h-screen flex flex-col relative shadow-xl overflow-hidden">
            
            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto pb-16">
                {{ $slot }}
            </main>

            <!-- Bottom Navigation -->
            @auth
                @include('livewire.mobile.partials.bottom-nav')
            @endauth

        </div>

        @livewireScripts
    </body>
</html>
