<x-filament-panels::page>
    @once
        <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    @endonce
    @php
        $stats = $this->stats;
        $students = $this->students;
        $map = $this->attendanceMap;
        $classes = \App\Models\SchoolClass::where('is_active', true)->orderBy('grade')->orderBy('section')->get();
        $isAdmin = auth()->user()->hasAnyRole(['super_admin', 'admin']);
        $totalStudents = $students->count();
        $tercatat = $totalStudents - ($stats['belum'] ?? 0);
        $progress = $totalStudents > 0 ? round(($tercatat / $totalStudents) * 100) : 0;
        $currentClass = $classes->firstWhere('id', $school_class_id);
        $hijri = \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
    @endphp

    {{-- HERO HEADER --}}
    <div style="position:relative; overflow:hidden; border-radius:16px; padding:24px 28px; color:white; background:linear-gradient(135deg, #059669 0%, #0d9488 50%, #0891b2 100%); box-shadow:0 10px 25px -5px rgba(0,0,0,0.15), 0 8px 10px -6px rgba(0,0,0,0.1);">
        <div style="position:absolute; right:-48px; top:-48px; width:224px; height:224px; border-radius:9999px; background:rgba(255,255,255,0.1); filter:blur(48px);"></div>
        <div style="position:absolute; left:-32px; bottom:-32px; width:192px; height:192px; border-radius:9999px; background:rgba(34,211,238,0.2); filter:blur(48px);"></div>
        <div style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.05), transparent);"></div>

        <div style="position:relative; display:flex; align-items:center; justify-content:space-between; gap:1.5rem; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:1rem; flex:1; min-width:280px;">
                <div style="width:64px; height:64px; border-radius:16px; background:rgba(255,255,255,0.2); backdrop-filter:blur(12px); display:flex; align-items:center; justify-content:center; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1); flex-shrink:0; border:2px solid rgba(255,255,255,0.3);">
                    <svg style="width:30px;height:30px;" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <div style="display:inline-flex; align-items:center; gap:6px; padding:3px 10px; border-radius:9999px; background:rgba(255,255,255,0.15); font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase;">
                        <span style="width:6px;height:6px;border-radius:9999px;background:#86efac;display:inline-block;"></span>
                        Akademik · Absensi
                    </div>
                    <h2 style="font-size:26px; font-weight:800; letter-spacing:-0.025em; line-height:1.15; margin-top:6px;">Absensi Hari Ini</h2>
                    <p style="font-size:13px; opacity:0.92; margin-top:4px; text-transform:capitalize;">{{ $hijri }}{{ $currentClass ? ' · Kelas '.$currentClass->name : '' }}</p>
                </div>
            </div>

            {{-- Progress ring --}}
            <div style="display:flex; align-items:center; gap:1rem;">
                <div style="text-align:right;">
                    <div style="font-size:10px; text-transform:uppercase; letter-spacing:0.1em; opacity:0.85; font-weight:700;">Sudah Absen</div>
                    <div style="font-size:30px; font-weight:800; line-height:1.1; margin-top:2px;">{{ $tercatat }}<span style="font-size:15px; opacity:0.8;">/{{ $totalStudents }}</span></div>
                    <div style="font-size:11px; opacity:0.9;">{{ $progress }}% selesai</div>
                </div>
                <div style="position:relative; width:80px; height:80px; flex-shrink:0;">
                    @php
                        $circ = 2 * 3.1416 * 32;
                        $offset = $circ - ($circ * $progress / 100);
                    @endphp
                    <svg style="width:80px;height:80px;transform:rotate(-90deg);filter:drop-shadow(0 2px 4px rgba(0,0,0,0.15));" viewBox="0 0 72 72">
                        <circle cx="36" cy="36" r="32" stroke="rgba(255,255,255,0.25)" stroke-width="6" fill="none"/>
                        <circle cx="36" cy="36" r="32" stroke="white" stroke-width="6" fill="none"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circ }}"
                                stroke-dashoffset="{{ $offset }}"/>
                    </svg>
                    <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; font-size:17px; font-weight:800;">{{ $progress }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- CONTROLS --}}
    <div style="border-radius:16px; border:1px solid rgba(229,231,235,0.6); background:rgba(255,255,255,0.9); padding:18px 20px; box-shadow:0 1px 3px 0 rgba(0,0,0,0.05), 0 1px 2px -1px rgba(0,0,0,0.05);" class="dark:bg-white/5 dark:border-white/10">
        <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:14px; padding-bottom:12px; border-bottom:1px solid rgba(229,231,235,0.6);">
            <div style="width:28px; height:28px; border-radius:8px; background:linear-gradient(135deg,#10b981,#0d9488); display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            </div>
            <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-700 dark:text-gray-200">Filter & Mode</div>
        </div>

        <div style="display:grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 1rem; align-items: end;">
            <div style="grid-column: span 5;">
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Kelas</label>
                <select wire:change="setClass($event.target.value === '' ? null : Number($event.target.value))"
                        style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600;"
                        class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" @selected($school_class_id === $c->id)>{{ $c->name }} &middot; {{ $c->academic_year }}</option>
                    @endforeach
                </select>
            </div>
            <div style="grid-column: span 3;">
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Tanggal</label>
                <input type="date" value="{{ $date }}"
                       @if(! $isAdmin) disabled @endif
                       wire:change="setDate($event.target.value)"
                       style="width:100%; border-radius:10px; padding:9px 12px; font-size:13px; font-weight:600; @if(! $isAdmin) opacity:0.6; cursor:not-allowed; @endif"
                       class="border-gray-300 dark:border-white/10 dark:bg-white/5 dark:text-white">
                @unless($isAdmin)
                    <p style="font-size:10px; margin-top:4px;" class="text-gray-500">🔒 Hanya admin</p>
                @endunless
            </div>
            <div style="grid-column: span 4;">
                <label style="display:block; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:6px;" class="text-gray-500 dark:text-gray-400">Mode Input</label>
                <div style="display:inline-flex; width:100%; border-radius:10px; padding:4px; gap:2px;" class="bg-gray-100 dark:bg-white/5 ring-1 ring-gray-200 dark:ring-white/10">
                    <button type="button" wire:click="setTab('scan')"
                            style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:7px 12px; border-radius:8px; font-size:13px; font-weight:700; transition:all 0.2s;"
                            class="{{ $tab === 'scan' ? 'bg-white dark:bg-white/15 text-emerald-700 dark:text-emerald-300 shadow-sm ring-1 ring-emerald-200 dark:ring-emerald-700' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                        Scan QR
                    </button>
                    <button type="button" wire:click="setTab('manual')"
                            style="flex:1; display:inline-flex; align-items:center; justify-content:center; gap:6px; padding:7px 12px; border-radius:8px; font-size:13px; font-weight:700; transition:all 0.2s;"
                            class="{{ $tab === 'manual' ? 'bg-white dark:bg-white/15 text-emerald-700 dark:text-emerald-300 shadow-sm ring-1 ring-emerald-200 dark:ring-emerald-700' : 'text-gray-500 hover:text-gray-700' }}">
                        <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        Manual
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- STATS PILLS --}}
    @php
        $cards = [
            ['key' => 'hadir', 'label' => 'Hadir', 'icon' => 'M5 13l4 4L19 7', 'gradient' => 'linear-gradient(135deg,#10b981,#16a34a)', 'accent' => '#10b981', 'text' => 'text-emerald-600'],
            ['key' => 'terlambat', 'label' => 'Terlambat', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'gradient' => 'linear-gradient(135deg,#f97316,#d97706)', 'accent' => '#f97316', 'text' => 'text-orange-600'],
            ['key' => 'izin', 'label' => 'Izin', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'gradient' => 'linear-gradient(135deg,#3b82f6,#0284c7)', 'accent' => '#3b82f6', 'text' => 'text-blue-600'],
            ['key' => 'sakit', 'label' => 'Sakit', 'icon' => 'M21 8a2 2 0 00-2-2h-3.293a1 1 0 01-.707-.293L13.293 3.293A1 1 0 0012.586 3H11.41a1 1 0 00-.707.293L8.997 5.707A1 1 0 018.29 6H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8z', 'gradient' => 'linear-gradient(135deg,#f59e0b,#ca8a04)', 'accent' => '#f59e0b', 'text' => 'text-amber-600'],
            ['key' => 'alpa', 'label' => 'Alpa', 'icon' => 'M6 18L18 6M6 6l12 12', 'gradient' => 'linear-gradient(135deg,#f43f5e,#dc2626)', 'accent' => '#f43f5e', 'text' => 'text-rose-600'],
            ['key' => 'belum', 'label' => 'Belum', 'icon' => 'M12 8v4l3 3', 'gradient' => 'linear-gradient(135deg,#9ca3af,#6b7280)', 'accent' => '#9ca3af', 'text' => 'text-gray-600'],
        ];
    @endphp
    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.75rem;">
        @foreach($cards as $c)
            <div style="position:relative; overflow:hidden; border-radius:12px; border:1px solid rgba(229,231,235,0.6); padding:14px 14px 14px 14px; box-shadow:0 1px 2px 0 rgba(0,0,0,0.05); transition:all 0.2s;" class="bg-white dark:bg-white/5 dark:border-white/10 hover:shadow-md">
                <div style="position:absolute; left:0; top:0; bottom:0; width:4px; background:{{ $c['gradient'] }};"></div>
                <div style="position:absolute; right:-16px; top:-16px; width:64px; height:64px; border-radius:9999px; background:{{ $c['gradient'] }}; opacity:0.12; filter:blur(8px);"></div>

                <div style="position:relative; display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                    <div style="width:32px; height:32px; border-radius:9px; background:{{ $c['gradient'] }}; display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                        <svg style="width:15px;height:15px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['icon'] }}"/></svg>
                    </div>
                    <div style="font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-500 dark:text-gray-400">{{ $c['label'] }}</div>
                </div>
                <div style="position:relative; display:flex; align-items:baseline; gap:4px;">
                    <div class="{{ $c['text'] }} dark:text-white" style="font-size:28px; font-weight:800; line-height:1;">{{ $stats[$c['key']] ?? 0 }}</div>
                    @if($totalStudents > 0)
                        <div style="font-size:11px; font-weight:600;" class="text-gray-400 dark:text-gray-500">/ {{ $totalStudents }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    {{-- TAB CONTENT --}}
    @if($tab === 'scan')
        <div class="rounded-2xl border border-gray-200/60 dark:border-white/10 bg-white/90 dark:bg-white/5 backdrop-blur-sm p-6 shadow-md space-y-5"
             x-data="qrScanner()" x-init="init()">

            {{-- Title row --}}
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding-bottom:1rem; border-bottom:1px solid rgba(229,231,235,0.6);">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;" class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <div class="text-base font-extrabold text-gray-800 dark:text-gray-100">Scan QR Kartu Pelajar</div>
                            <span style="display:inline-flex; align-items:center; gap:5px; padding:2px 9px; border-radius:9999px; font-size:10px; font-weight:700; background-color:rgba(16,185,129,0.1); color:#047857;" class="dark:text-emerald-300">
                                <span :style="running ? 'background:#10b981;animation:pulse 2s infinite;' : 'background:#9ca3af;'" style="width:6px;height:6px;border-radius:9999px;display:inline-block;"></span>
                                <span x-text="running ? 'AKTIF' : 'STAND BY'"></span>
                            </span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Arahkan QR kartu siswa ke kamera. Status otomatis tercatat sebagai <strong class="text-emerald-700 dark:text-emerald-300">Hadir</strong>.</div>
                    </div>
                </div>
                <div style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:10px; background:rgba(243,244,246,0.8); font-size:11px; font-weight:700;" class="dark:bg-white/5 ring-1 ring-gray-200/60 dark:ring-white/10 text-gray-600 dark:text-gray-300">
                    <svg style="width:14px;height:14px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Aman & Real-time
                </div>
            </div>

            {{-- Last scan banner --}}
            @if($lastScanMessage)
                @php
                    $bg = match($lastScanType) {
                        'success' => 'bg-emerald-50 border-emerald-300 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300',
                        'warning' => 'bg-amber-50 border-amber-300 text-amber-800 dark:bg-amber-900/20 dark:text-amber-300',
                        'danger'  => 'bg-rose-50 border-rose-300 text-rose-800 dark:bg-rose-900/20 dark:text-rose-300',
                        default   => 'bg-gray-50 border-gray-300 text-gray-800',
                    };
                    $icon = match($lastScanType) {
                        'success' => 'M5 13l4 4L19 7',
                        'warning' => 'M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4a2 2 0 00-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z',
                        'danger'  => 'M6 18L18 6M6 6l12 12',
                        default   => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                    };
                @endphp
                <div class="rounded-xl border-2 {{ $bg }} px-4 py-3 flex items-center gap-3 shadow-sm">
                    <svg style="width:18px;height:18px;" class="flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                    <div class="text-sm font-bold flex-1">{{ $lastScanMessage }}</div>
                </div>
            @endif

            {{-- Scanner area --}}
            <div style="display:grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 1.25rem;" class="scanner-grid">
                <div style="grid-column: span 3;" class="scanner-cam">
                    <div style="position:relative; border-radius:20px; overflow:hidden; aspect-ratio:1/1; max-width:480px; margin:0 auto; background:radial-gradient(circle at 30% 20%, #1e293b 0%, #0f172a 60%, #020617 100%); box-shadow:0 20px 40px -10px rgba(0,0,0,0.4), inset 0 0 0 1px rgba(255,255,255,0.05);"
                         :class="running ? '' : 'flex items-center justify-center'">
                        <div id="qr-reader" style="width:100%; height:100%;" :class="running ? '' : 'hidden'"></div>

                        {{-- Idle state --}}
                        <div x-show="!running" style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:24px; text-align:center; color:rgba(255,255,255,0.85);">
                            <div style="position:relative; width:96px; height:96px; margin:0 auto 16px; display:flex; align-items:center; justify-content:center;">
                                <div style="position:absolute; inset:0; border-radius:24px; background:linear-gradient(135deg, rgba(16,185,129,0.3), rgba(13,148,136,0.2)); filter:blur(20px); animation:pulse 3s ease-in-out infinite;"></div>
                                <div style="position:relative; width:96px; height:96px; border-radius:24px; background:linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)); border:1px solid rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; backdrop-filter:blur(8px);">
                                    <svg style="width:44px;height:44px;color:#10b981;" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>
                            <div style="font-size:16px; font-weight:800; color:white; letter-spacing:-0.01em;">Kamera Belum Aktif</div>
                            <div style="font-size:12px; opacity:0.7; margin-top:6px; max-width:280px;">Klik tombol <strong style="color:#10b981;">Mulai Scan</strong> untuk mengaktifkan kamera dan mulai memindai QR siswa.</div>
                        </div>

                        {{-- Scan overlay frame --}}
                        <div x-show="running" x-cloak style="position:absolute; inset:0; pointer-events:none;">
                            <div style="position:absolute; inset:32px; border:2px dashed rgba(255,255,255,0.25); border-radius:16px;"></div>
                            <div style="position:absolute; top:32px; left:32px; width:36px; height:36px; border-left:4px solid #10b981; border-top:4px solid #10b981; border-top-left-radius:14px; box-shadow:-2px -2px 8px rgba(16,185,129,0.4);"></div>
                            <div style="position:absolute; top:32px; right:32px; width:36px; height:36px; border-right:4px solid #10b981; border-top:4px solid #10b981; border-top-right-radius:14px; box-shadow:2px -2px 8px rgba(16,185,129,0.4);"></div>
                            <div style="position:absolute; bottom:32px; left:32px; width:36px; height:36px; border-left:4px solid #10b981; border-bottom:4px solid #10b981; border-bottom-left-radius:14px; box-shadow:-2px 2px 8px rgba(16,185,129,0.4);"></div>
                            <div style="position:absolute; bottom:32px; right:32px; width:36px; height:36px; border-right:4px solid #10b981; border-bottom:4px solid #10b981; border-bottom-right-radius:14px; box-shadow:2px 2px 8px rgba(16,185,129,0.4);"></div>
                            {{-- Animated scan line --}}
                            <div style="position:absolute; left:36px; right:36px; height:2px; background:linear-gradient(90deg, transparent, #10b981, transparent); box-shadow:0 0 10px #10b981; animation:scanline 2s ease-in-out infinite;"></div>
                        </div>
                    </div>
                </div>

                <div style="grid-column: span 2; display:flex; flex-direction:column; gap:14px;" class="scanner-side">
                    {{-- Action buttons --}}
                    <div>
                        <button type="button" @click="start()" x-show="!running"
                                style="width:100%; display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:14px 20px; border-radius:14px; font-size:14px; font-weight:800; color:white; background:linear-gradient(135deg,#059669 0%,#0d9488 50%,#0891b2 100%); border:none; cursor:pointer; box-shadow:0 8px 20px -4px rgba(16,185,129,0.45), 0 4px 6px -2px rgba(0,0,0,0.05); transition:all 0.25s; position:relative; overflow:hidden;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 28px -4px rgba(16,185,129,0.55), 0 6px 10px -2px rgba(0,0,0,0.1)';"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 20px -4px rgba(16,185,129,0.45), 0 4px 6px -2px rgba(0,0,0,0.05)';">
                            <span style="position:absolute; inset:0; background:linear-gradient(135deg, rgba(255,255,255,0.15), transparent);"></span>
                            <svg style="width:18px;height:18px;position:relative;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span style="position:relative; letter-spacing:0.02em;">MULAI SCAN</span>
                        </button>
                        <button type="button" @click="stop()" x-show="running" x-cloak
                                style="width:100%; display:inline-flex; align-items:center; justify-content:center; gap:10px; padding:14px 20px; border-radius:14px; font-size:14px; font-weight:800; color:white; background:linear-gradient(135deg,#e11d48,#be123c); border:none; cursor:pointer; box-shadow:0 8px 20px -4px rgba(225,29,72,0.45), 0 4px 6px -2px rgba(0,0,0,0.05); transition:all 0.25s;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 12px 28px -4px rgba(225,29,72,0.55), 0 6px 10px -2px rgba(0,0,0,0.1)';"
                                onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 20px -4px rgba(225,29,72,0.45), 0 4px 6px -2px rgba(0,0,0,0.05)';">
                            <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span style="letter-spacing:0.02em;">HENTIKAN SCAN</span>
                        </button>

                        <div style="display:flex; align-items:center; gap:8px; margin-top:10px; padding:8px 12px; border-radius:10px; background:rgba(243,244,246,0.6); font-size:11px; font-weight:600;" class="dark:bg-white/5 ring-1 ring-gray-200/60 dark:ring-white/10">
                            <div style="width:8px; height:8px; border-radius:9999px;" :style="running ? 'background:#10b981; animation:pulse 1.5s infinite; box-shadow:0 0 6px #10b981;' : 'background:#9ca3af;'"></div>
                            <span class="text-gray-600 dark:text-gray-300" x-text="status || (running ? 'Memindai QR...' : 'Siap memindai')"></span>
                        </div>
                    </div>

                    {{-- Token input --}}
                    <div style="padding:14px; border-radius:12px; background:rgba(249,250,251,0.6); border:1px solid rgba(229,231,235,0.6);" class="dark:bg-white/5 dark:border-white/10">
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                            <svg style="width:14px;height:14px;color:#6b7280;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            <label style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-gray-600 dark:text-gray-300">Input Token Manual</label>
                        </div>
                        <p style="font-size:11px; margin-bottom:8px;" class="text-gray-500 dark:text-gray-400">Untuk perangkat tanpa kamera.</p>
                        <form wire:submit.prevent="markByToken($event.target.token.value); $event.target.reset()" style="display:flex; gap:6px;">
                            <input name="token" type="text" placeholder="Ketik token QR..."
                                   style="flex:1; padding:8px 12px; border-radius:8px; font-size:12px; font-family:ui-monospace,monospace; text-transform:uppercase; border:1px solid rgba(229,231,235,0.8);"
                                   class="dark:bg-white/5 dark:border-white/10 dark:text-white">
                            <button type="submit"
                                    style="padding:8px 14px; border-radius:8px; font-size:12px; font-weight:700; color:white; background:linear-gradient(135deg,#374151,#1f2937); border:none; cursor:pointer; transition:all 0.2s;"
                                    onmouseover="this.style.transform='translateY(-1px)';"
                                    onmouseout="this.style.transform='translateY(0)';">
                                Cek
                            </button>
                        </form>
                    </div>

                    {{-- Tips --}}
                    <div style="padding:14px; border-radius:12px; background:linear-gradient(135deg, rgba(16,185,129,0.08), rgba(13,148,136,0.04)); border:1px solid rgba(16,185,129,0.2);" class="dark:border-emerald-800/40">
                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                            <div style="width:20px; height:20px; border-radius:6px; background:linear-gradient(135deg,#10b981,#0d9488); display:flex; align-items:center; justify-content:center; color:white;">
                                <svg style="width:11px;height:11px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div style="font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em;" class="text-emerald-700 dark:text-emerald-300">Tips Scan</div>
                        </div>
                        <ul style="font-size:11px; padding:0; margin:0; list-style:none;" class="text-emerald-900 dark:text-emerald-200 space-y-1.5">
                            <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:800;">✓</span><span>Pegang kartu 10–20 cm dari kamera</span></li>
                            <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:800;">✓</span><span>Pastikan QR utuh dan tidak silau</span></li>
                            <li style="display:flex; align-items:flex-start; gap:6px;"><span style="color:#10b981; font-weight:800;">✓</span><span>Beep berbunyi setiap scan berhasil</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <style>
            @keyframes scanline {
                0% { top: 36px; opacity: 0; }
                10% { opacity: 1; }
                50% { opacity: 1; }
                90% { opacity: 1; }
                100% { top: calc(100% - 38px); opacity: 0; }
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; }
                50% { opacity: 0.6; }
            }
        </style>

        <script>
            window.qrScanner = function () {
                return {
                    running: false,
                    instance: null,
                    status: '',
                    cooldown: false,
                    init() { this.$nextTick(() => {}); },
                    async start() {
                        if (typeof Html5Qrcode === 'undefined') {
                            this.status = 'Memuat library QR...';
                            await new Promise((resolve, reject) => {
                                let tries = 0;
                                const check = setInterval(() => {
                                    if (typeof Html5Qrcode !== 'undefined') { clearInterval(check); resolve(); }
                                    else if (++tries > 20) { clearInterval(check); reject(new Error('Library QR gagal dimuat.')); }
                                }, 200);
                            }).catch(e => { this.status = e.message; return Promise.reject(e); });
                        }
                        try {
                            this.instance = new Html5Qrcode('qr-reader');
                            this.running = true;
                            this.status = 'Mengaktifkan kamera...';
                            await this.instance.start(
                                { facingMode: 'environment' },
                                { fps: 10, qrbox: { width: 240, height: 240 } },
                                (decodedText) => this.onScan(decodedText),
                                () => {}
                            );
                            this.status = 'Arahkan QR siswa ke kamera';
                        } catch (e) {
                            console.error(e);
                            this.status = 'Gagal: ' + (e?.message || e);
                            this.running = false;
                        }
                    },
                    async stop() {
                        if (this.instance) {
                            try { await this.instance.stop(); } catch (_) {}
                            try { await this.instance.clear(); } catch (_) {}
                        }
                        this.instance = null;
                        this.running = false;
                        this.status = 'Scanner berhenti';
                    },
                    onScan(text) {
                        if (this.cooldown) return;
                        this.cooldown = true;
                        this.beep();
                        try { $wire.markByToken(text); } catch (e) { console.error(e); }
                        setTimeout(() => { this.cooldown = false; }, 1500);
                    },
                    beep() {
                        try {
                            const ctx = new (window.AudioContext || window.webkitAudioContext)();
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc.frequency.value = 880;
                            osc.connect(gain); gain.connect(ctx.destination);
                            gain.gain.setValueAtTime(0.15, ctx.currentTime);
                            gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.15);
                            osc.start(); osc.stop(ctx.currentTime + 0.15);
                        } catch (_) {}
                    },
                };
            };
        </script>
    @endif

    @if($tab === 'manual')
        <div class="rounded-2xl border border-gray-200/60 dark:border-white/10 bg-white/90 dark:bg-white/5 backdrop-blur-sm p-6 shadow-md space-y-5"
             x-data="{ open: null, note: '', modal: { id: null, name: '', nis: '', photo: '', initial: '', status: '', currentNote: '' } }"
             @keydown.escape.window="open = null; modal.id = null">

            {{-- Title --}}
            <div style="display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap; padding-bottom:1rem; border-bottom:1px solid rgba(229,231,235,0.6);">
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <div style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;" class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white shadow-md">
                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <div>
                        <div style="display:flex; align-items:center; gap:0.5rem;">
                            <div class="text-base font-extrabold text-gray-800 dark:text-gray-100">Input Manual</div>
                            <span style="display:inline-flex; align-items:center; padding:2px 8px; border-radius:9999px; font-size:10px; font-weight:700; background-color:rgba(16,185,129,0.1); color:#047857;" class="dark:text-emerald-300">{{ $totalStudents }} Siswa</span>
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Klik tombol "Atur" pada kolom aksi untuk mengubah status.</div>
                    </div>
                </div>
                <div style="display:inline-flex; align-items:center; gap:0.5rem; padding:4px; border-radius:10px; background-color:rgba(243,244,246,0.8);" class="dark:bg-white/5 ring-1 ring-gray-200/60 dark:ring-white/10">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 px-2">Aksi Cepat</span>
                    <button type="button" wire:click="bulkMarkRemaining('hadir')"
                            wire:confirm="Tandai semua siswa yang BELUM tercatat sebagai HADIR?"
                            style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:700;"
                            class="bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white shadow-sm transition-all">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        Sisa Hadir
                    </button>
                    <button type="button" wire:click="bulkMarkRemaining('alpa')"
                            wire:confirm="Tandai semua siswa yang BELUM tercatat sebagai ALPA?"
                            style="display:inline-flex; align-items:center; gap:6px; padding:6px 12px; border-radius:8px; font-size:12px; font-weight:700;"
                            class="bg-gradient-to-br from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white shadow-sm transition-all">
                        <svg style="width:13px;height:13px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        Sisa Alpa
                    </button>
                </div>
            </div>

            @if($students->isEmpty())
                <div class="rounded-xl border-2 border-dashed border-gray-300/60 dark:border-white/10 p-10 text-center text-gray-500 text-sm">
                    Pilih kelas terlebih dahulu untuk menampilkan siswa.
                </div>
            @else
                <div class="rounded-xl border border-gray-200/60 dark:border-white/10 bg-white/90 dark:bg-white/5 backdrop-blur-sm overflow-hidden">
                    <table style="width:100%; border-collapse:collapse;" class="text-left">
                        <thead style="background-color: rgba(249, 250, 251, 0.8);" class="dark:bg-white/5">
                            <tr>
                                <th style="padding:8px 12px; border-right:1px solid rgba(229,231,235,0.6); border-bottom:1px solid rgba(229,231,235,0.6); width:64px;" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Foto</th>
                                <th style="padding:8px 12px; border-right:1px solid rgba(229,231,235,0.6); border-bottom:1px solid rgba(229,231,235,0.6);" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Siswa</th>
                                <th style="padding:8px 12px; border-right:1px solid rgba(229,231,235,0.6); border-bottom:1px solid rgba(229,231,235,0.6);" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th style="padding:8px 12px; border-right:1px solid rgba(229,231,235,0.6); border-bottom:1px solid rgba(229,231,235,0.6);" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">NIS</th>
                                <th style="padding:8px 12px; border-right:1px solid rgba(229,231,235,0.6); border-bottom:1px solid rgba(229,231,235,0.6);" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</th>
                                <th style="padding:8px 12px; border-bottom:1px solid rgba(229,231,235,0.6); width:110px;" class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $s)
                            @php
                                $rec = $map->get($s->id);
                                $status = $rec?->status;
                                $cfg = match($status) {
                                    'hadir'     => ['bg-emerald-500', 'Hadir', 'text-emerald-700 dark:text-emerald-300', 'rgba(16, 185, 129, 0.05)'],
                                    'terlambat' => ['bg-orange-500', 'Terlambat', 'text-orange-700 dark:text-orange-300', 'rgba(249, 115, 22, 0.05)'],
                                    'izin'      => ['bg-blue-500', 'Izin', 'text-blue-700 dark:text-blue-300', 'rgba(59, 130, 246, 0.05)'],
                                    'sakit'     => ['bg-amber-500', 'Sakit', 'text-amber-700 dark:text-amber-300', 'rgba(245, 158, 11, 0.05)'],
                                    'alpa'      => ['bg-rose-500', 'Alpa', 'text-rose-700 dark:text-rose-300', 'rgba(244, 63, 94, 0.05)'],
                                    default     => ['bg-gray-400', 'Belum', 'text-gray-600 dark:text-gray-400', 'transparent'],
                                };
                                $isLast = $loop->last;
                                $borderBottom = $isLast ? '' : 'border-bottom:1px solid rgba(229,231,235,0.6);';
                            @endphp
                            <tr style="background-color: {{ $cfg[3] }}; position:relative;" class="transition-colors hover:bg-gray-50 dark:hover:bg-white/5">
                                {{-- Photo --}}
                                <td style="padding:6px 12px; border-right:1px solid rgba(229,231,235,0.6); {{ $borderBottom }} vertical-align:middle;">
                                    @if($s->photo_url)
                                        <img src="{{ $s->photo_url }}" style="width:34px;height:34px;border-radius:9999px;object-fit:cover;" class="ring-2 ring-white shadow-sm">
                                    @else
                                        <div style="width:34px;height:34px;border-radius:9999px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:13px;" class="bg-gradient-to-br from-emerald-500 to-teal-600 text-white ring-2 ring-white shadow-sm">{{ mb_substr($s->name, 0, 1) }}</div>
                                    @endif
                                </td>

                                {{-- Name --}}
                                <td style="padding:6px 12px; border-right:1px solid rgba(229,231,235,0.6); {{ $borderBottom }} vertical-align:middle;">
                                    <div class="text-sm font-bold text-gray-800 dark:text-gray-100">{{ $s->name }}</div>
                                </td>

                                {{-- Status --}}
                                <td style="padding:6px 12px; border-right:1px solid rgba(229,231,235,0.6); {{ $borderBottom }} vertical-align:middle; white-space:nowrap;">
                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 9px; border-radius:9999px; font-size:11px; font-weight:700;" class="text-white {{ $cfg[0] }} shadow-sm">
                                        @if($status)
                                            <svg style="width:6px;height:6px;" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                        @endif
                                        {{ $cfg[1] }}
                                    </span>
                                </td>

                                {{-- NIS --}}
                                <td style="padding:6px 12px; border-right:1px solid rgba(229,231,235,0.6); {{ $borderBottom }} vertical-align:middle; white-space:nowrap;">
                                    <div class="text-xs text-gray-500 dark:text-gray-400" style="font-family:ui-monospace,monospace;">{{ $s->nis }}</div>
                                </td>

                                {{-- Note --}}
                                <td style="padding:6px 12px; border-right:1px solid rgba(229,231,235,0.6); {{ $borderBottom }} vertical-align:middle;">
                                    @if($rec?->note)
                                        <span class="text-xs text-gray-500 dark:text-gray-400 italic" title="{{ $rec->note }}">📝 {{ $rec->note }}</span>
                                    @else
                                        <span class="text-xs text-gray-400 dark:text-gray-500">-</span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td style="padding:6px 12px; {{ $borderBottom }} vertical-align:middle; text-align:center;">
                                    @php
                                        $btnLabel = $status ? 'Ubah' : 'Atur';
                                        $btnGradient = $status
                                            ? 'linear-gradient(135deg,#6366f1,#4f46e5)'
                                            : 'linear-gradient(135deg,#10b981,#0d9488)';
                                    @endphp
                                    <button type="button"
                                            @click="modal.id = {{ $s->id }}; modal.name = @js($s->name); modal.nis = @js($s->nis); modal.photo = @js($s->photo_url ?? ''); modal.initial = @js(mb_substr($s->name, 0, 1)); modal.status = @js($status ?? ''); modal.currentNote = @js($rec?->note ?? ''); note = @js($rec?->note ?? '')"
                                            style="display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border-radius:8px; font-size:11px; font-weight:700; color:white; background:{{ $btnGradient }}; box-shadow:0 1px 3px rgba(0,0,0,0.12); transition:all 0.2s; border:none; cursor:pointer;"
                                            onmouseover="this.style.transform='translateY(-1px)';this.style.boxShadow='0 4px 8px rgba(0,0,0,0.15)';"
                                            onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 1px 3px rgba(0,0,0,0.12)';">
                                        <svg style="width:12px;height:12px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        {{ $btnLabel }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Professional Modal Popup --}}
            <div x-show="modal.id !== null" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="modal.id = null"
                 style="position:fixed; inset:0; z-index:9999; display:flex; align-items:center; justify-content:center; padding:1rem; background:rgba(15,23,42,0.6); backdrop-filter:blur(4px);">

                <div @click.stop
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     style="width:100%; max-width:520px; border-radius:20px; overflow:hidden; background:white; box-shadow:0 25px 50px -12px rgba(0,0,0,0.4);"
                     class="dark:bg-gray-800">

                    {{-- Modal Header --}}
                    <div style="position:relative; padding:24px; background:linear-gradient(135deg,#059669 0%,#0d9488 50%,#0891b2 100%); color:white; overflow:hidden;">
                        <div style="position:absolute; right:-40px; top:-40px; width:160px; height:160px; border-radius:9999px; background:rgba(255,255,255,0.1); filter:blur(40px);"></div>
                        <div style="position:absolute; left:-30px; bottom:-30px; width:120px; height:120px; border-radius:9999px; background:rgba(34,211,238,0.2); filter:blur(40px);"></div>

                        <button type="button" @click="modal.id = null"
                                style="position:absolute; top:14px; right:14px; width:32px; height:32px; border-radius:9999px; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; color:white; border:none; cursor:pointer; backdrop-filter:blur(8px); transition:all 0.2s;"
                                onmouseover="this.style.background='rgba(255,255,255,0.35)';"
                                onmouseout="this.style.background='rgba(255,255,255,0.2)';">
                            <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <div style="position:relative; display:flex; align-items:center; gap:16px;">
                            <template x-if="modal.photo">
                                <img :src="modal.photo" style="width:64px;height:64px;border-radius:9999px;object-fit:cover;border:3px solid rgba(255,255,255,0.4); box-shadow:0 4px 12px rgba(0,0,0,0.15);">
                            </template>
                            <template x-if="!modal.photo">
                                <div style="width:64px;height:64px;border-radius:9999px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:24px; background:rgba(255,255,255,0.25); color:white; border:3px solid rgba(255,255,255,0.4); box-shadow:0 4px 12px rgba(0,0,0,0.15);" x-text="modal.initial"></div>
                            </template>
                            <div style="flex:1; min-width:0;">
                                <div style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; opacity:0.85;">Atur Absensi</div>
                                <div style="font-size:20px; font-weight:800; line-height:1.2; margin-top:2px;" x-text="modal.name"></div>
                                <div style="font-size:12px; opacity:0.9; font-family:ui-monospace,monospace; margin-top:2px;">NIS: <span x-text="modal.nis"></span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div style="padding:24px;">
                        <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:12px;" class="text-gray-600 dark:text-gray-300">Pilih Status Kehadiran</div>

                        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:10px;">
                            @php
                                $statuses = [
                                    'hadir'     => ['Hadir', 'M5 13l4 4L19 7', 'linear-gradient(135deg,#10b981,#16a34a)', '#10b981'],
                                    'terlambat' => ['Terlambat', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'linear-gradient(135deg,#f97316,#d97706)', '#f97316'],
                                    'izin'      => ['Izin', 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'linear-gradient(135deg,#3b82f6,#0284c7)', '#3b82f6'],
                                    'sakit'     => ['Sakit', 'M21 8a2 2 0 00-2-2h-3.293a1 1 0 01-.707-.293L13.293 3.293A1 1 0 0012.586 3H11.41a1 1 0 00-.707.293L8.997 5.707A1 1 0 018.29 6H5a2 2 0 00-2 2v8a2 2 0 002 2h14a2 2 0 002-2V8z', 'linear-gradient(135deg,#f59e0b,#ca8a04)', '#f59e0b'],
                                    'alpa'      => ['Alpa', 'M6 18L18 6M6 6l12 12', 'linear-gradient(135deg,#f43f5e,#dc2626)', '#f43f5e'],
                                ];
                            @endphp
                            @foreach($statuses as $key => $st)
                                <button type="button"
                                        @click="$wire.setStatus(modal.id, '{{ $key }}', note || null); modal.id = null"
                                        :style="modal.status === '{{ $key }}' ? 'background:{{ $st[2] }}; color:white; border-color:transparent; box-shadow:0 4px 12px rgba(0,0,0,0.15);' : ''"
                                        style="position:relative; display:flex; flex-direction:column; align-items:center; gap:6px; padding:14px 12px; border-radius:12px; border:2px solid rgba(229,231,235,0.8); background:white; cursor:pointer; transition:all 0.2s; font-weight:700;"
                                        class="dark:bg-white/5 dark:border-white/10"
                                        onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 16px rgba(0,0,0,0.12)';"
                                        onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='';">
                                    <div style="width:36px; height:36px; border-radius:10px; background:{{ $st[2] }}; display:flex; align-items:center; justify-content:center; color:white; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                                        <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $st[1] }}"/></svg>
                                    </div>
                                    <div style="font-size:13px;" :class="modal.status === '{{ $key }}' ? 'text-white' : ''" class="text-gray-700 dark:text-gray-200">{{ $st[0] }}</div>
                                    <template x-if="modal.status === '{{ $key }}'">
                                        <div style="position:absolute; top:6px; right:6px; width:18px; height:18px; border-radius:9999px; background:white; display:flex; align-items:center; justify-content:center;">
                                            <svg style="width:11px;height:11px;color:{{ $st[3] }};" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    </template>
                                </button>
                            @endforeach
                        </div>

                        <div style="margin-top:20px;">
                            <label style="display:block; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.1em; margin-bottom:8px;" class="text-gray-600 dark:text-gray-300">
                                Catatan <span style="font-weight:500; text-transform:none; letter-spacing:0; opacity:0.7;">(opsional)</span>
                            </label>
                            <textarea x-model="note" rows="2" placeholder="Tambahkan catatan untuk kehadiran ini..."
                                   style="width:100%; padding:10px 14px; border-radius:10px; font-size:13px; resize:vertical; border:1px solid rgba(229,231,235,0.8);"
                                   class="dark:bg-white/5 dark:border-white/10 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div style="padding:14px 24px; background:rgba(249,250,251,0.6); border-top:1px solid rgba(229,231,235,0.6); display:flex; justify-content:flex-end; gap:8px;" class="dark:bg-white/5 dark:border-white/10">
                        <button type="button" @click="modal.id = null"
                                style="padding:8px 18px; border-radius:9px; font-size:13px; font-weight:700; border:1px solid rgba(229,231,235,0.8); background:white; cursor:pointer; transition:all 0.2s;"
                                class="text-gray-700 dark:text-gray-200 dark:bg-white/5 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/10">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</x-filament-panels::page>
