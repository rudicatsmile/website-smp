<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @php $settings = app(\App\Settings\GeneralSettings::class); @endphp
    <title>{{ $title ?? 'Portal Siswa' }} &middot; {{ $settings->school_name }}</title>
    @if($settings->favicon)
        <link rel="icon" href="{{ asset('storage/'.$settings->favicon) }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    @auth
        @php
            $user = auth()->user();
            $student = $user->student;
            $isParent = $user->hasRole('parent');
            $portalHomeRoute = $isParent ? 'portal.parent.dashboard' : 'portal.dashboard';
            $portalLogoutRoute = $isParent ? 'portal.parent.logout' : 'portal.logout';
            $portalTitle = $isParent ? 'Portal Orang Tua' : 'Portal Siswa';
            $portalInitial = $isParent ? 'O' : 'S';
            $portalAccentFrom = $isParent ? 'from-indigo-500' : 'from-emerald-500';
            $portalAccentTo = $isParent ? 'to-blue-600' : 'to-teal-600';
        @endphp
        {{-- Top bar --}}
        <header class="sticky top-0 z-40 border-b border-white/70 bg-white/90 shadow-sm shadow-slate-200/70 backdrop-blur-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="flex items-center justify-between gap-4 py-3">
                    <div class="flex items-center gap-3">
                        <a href="{{ route($portalHomeRoute) }}" class="group flex items-center gap-3">
                            @if($settings->logo)
                                <img src="{{ asset('storage/'.$settings->logo) }}" alt="Logo" class="h-11 w-11 rounded-2xl object-cover ring-1 ring-slate-200 shadow-sm transition group-hover:scale-105">
                            @else
                                <div class="h-11 w-11 rounded-2xl bg-gradient-to-br {{ $portalAccentFrom }} {{ $portalAccentTo }} flex items-center justify-center text-white font-bold shadow-lg transition group-hover:scale-105">{{ $portalInitial }}</div>
                            @endif
                            <div class="hidden sm:block">
                                <div class="text-[15px] font-extrabold text-slate-900 leading-tight tracking-tight">{{ $portalTitle }}</div>
                                <div class="text-xs font-medium text-slate-500">{{ $settings->school_name }}</div>
                            </div>
                        </a>
                    </div>
                    <nav class="hidden lg:flex flex-1 items-center justify-center">
                        @php
                            if ($isParent) {
                                $nav = [
                                    ['route' => 'portal.parent.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ];
                            } else {
                                $nav = [
                                    ['route' => 'portal.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                    ['route' => 'portal.assignments.index', 'label' => 'Tugas', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                    ['route' => 'portal.announcements.index', 'label' => 'Pengumuman', 'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
                                    ['route' => 'portal.materials.index', 'label' => 'Materi', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
                                    ['route' => 'portal.quizzes.index', 'label' => 'Latihan', 'icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
                                    ['route' => 'portal.counseling.index', 'label' => 'BK', 'icon' => 'M4.318 6.318a4.5 4.5 0 016.364 0L12 7.636l1.318-1.318a4.5 4.5 0 116.364 6.364L12 20.364l-7.682-7.682a4.5 4.5 0 010-6.364z'],
                                ];
                                if ($student && $student->tahfidzParticipant?->is_active) {
                                    $nav[] = [
                                        'route'  => 'portal.tahfidz.raport',
                                        'params' => ['student' => $student->slug],
                                        'label'  => 'Tahfidz',
                                        'icon'   => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                                    ];
                                }
                            }
                        @endphp
                        <div class="flex items-center gap-2 rounded-full border border-slate-200/80 bg-white px-2 py-1.5 shadow-sm">
                            @foreach($nav as $item)
                                @php
                                    $active    = request()->routeIs($item['route'].'*');
                                    $navUrl    = route($item['route'], $item['params'] ?? []);
                                @endphp
                                <a href="{{ $navUrl }}" wire:navigate class="group relative inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold transition-all duration-200 {{ $active ? 'bg-gradient-to-r from-emerald-500 to-teal-500 text-white shadow-md shadow-emerald-500/20' : 'text-slate-600 hover:bg-slate-50 hover:text-emerald-700' }}">
                                    <svg style="width:17px;height:17px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="{{ $active ? 'text-white' : 'text-slate-400 group-hover:text-emerald-600' }}">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                                    </svg>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </nav>
                    <div class="flex items-center gap-3">
                        <a href="{{ $isParent ? route('portal.parent.dashboard') : route('portal.profile') }}" class="flex items-center gap-2 rounded-2xl border border-slate-200/80 bg-white px-2.5 py-1.5 shadow-sm transition {{ $isParent ? 'hover:border-indigo-200 hover:bg-indigo-50/40' : 'hover:border-emerald-200 hover:bg-emerald-50/40' }}">
                            @if($student?->photo_url)
                                <img src="{{ $student->photo_url }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-white shadow-sm" alt="">
                            @else
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $isParent ? 'from-indigo-100 to-blue-100 text-indigo-700' : 'from-emerald-100 to-teal-100 text-emerald-700' }} flex items-center justify-center text-sm font-extrabold ring-2 ring-white shadow-sm">{{ substr($user->name, 0, 1) }}</div>
                            @endif
                            <div class="hidden sm:block text-left">
                                <div class="text-xs font-bold text-slate-800 leading-tight">{{ $user->name }}</div>
                                <div class="text-[10px] font-medium {{ $isParent ? 'text-indigo-600' : 'text-emerald-600' }}">{{ $isParent ? 'Orang Tua' : ($student?->schoolClass?->name ?? 'Siswa') }}</div>
                            </div>
                        </a>
                        <form method="POST" action="{{ route($portalLogoutRoute) }}">
                            @csrf
                            <button type="submit" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-500 shadow-sm transition hover:border-red-200 hover:bg-red-50 hover:text-red-600" title="Logout">
                                <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            {{-- Mobile nav --}}
            <div class="lg:hidden border-t border-slate-100 bg-white/95 backdrop-blur-xl">
                <div class="flex items-center gap-2 overflow-x-auto px-4 py-2">
                    @foreach($nav as $item)
                        @php
                            $active    = request()->routeIs($item['route'].'*');
                            $navUrl    = route($item['route'], $item['params'] ?? []);
                        @endphp
                        <a href="{{ $navUrl }}" wire:navigate class="inline-flex shrink-0 items-center gap-2 rounded-full px-4 py-2 text-xs font-semibold transition {{ $active ? 'bg-emerald-600 text-white shadow-sm' : 'bg-slate-50 text-slate-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                            </svg>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </header>
    @endauth

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
        {{ $slot }}
    </main>

    @livewireScripts
    @stack('scripts')
</body>
</html>
