<x-filament-panels::page>
    @php
        $rec = $this->record;
        $user = auth()->user();
        $isAck = $user ? $rec->isAcknowledgedBy($user) : false;
        $priorityColor = match($rec->priority) {
            'urgent' => 'bg-red-50 text-red-700 border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
            'penting' => 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
            default => 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-500/10 dark:text-slate-400 dark:border-slate-500/20',
        };
    @endphp

    <div class="space-y-8 max-w-5xl mx-auto">
        {{-- Header Meta --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="flex flex-wrap items-center gap-2 mb-6">
                @if($rec->is_pinned)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 text-amber-700 text-xs font-semibold border border-amber-200 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20" style="line-height:1;">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width:14px;height:14px;flex-shrink:0;display:inline-block;"><path d="M5.5 3.5A1.5 1.5 0 017 2h6a1.5 1.5 0 011.5 1.5v8.379a1.5 1.5 0 01-.44 1.06l-3.939 3.94a.5.5 0 01-.707 0L5.94 12.94a1.5 1.5 0 01-.44-1.06V3.5z"/></svg>
                        <span>Disematkan</span>
                    </span>
                @endif
                <span class="inline-flex px-3 py-1.5 rounded-full border text-xs font-semibold {{ $priorityColor }}">
                    {{ $rec->priority_label }}
                </span>
                <span class="inline-flex px-3 py-1.5 rounded-full bg-blue-50 text-blue-700 text-xs font-semibold border border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20">
                    {{ $rec->category_label }}
                </span>
                @if($isAck)
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold border border-emerald-200 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20" style="line-height:1;">
                        <svg fill="currentColor" viewBox="0 0 20 20" style="width:14px;height:14px;flex-shrink:0;display:inline-block;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>Sudah Dibaca</span>
                    </span>
                @endif
            </div>

            <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-50 mb-6 leading-tight">{{ $rec->title }}</h1>

            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800 pt-4">
                <div class="flex items-center gap-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $rec->author?->name ?? '—' }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>{{ $rec->published_at?->translatedFormat('d MMMM Y, H:i') ?? 'Draft' }}</span>
                </div>
                @if($rec->expires_at)
                    <div class="flex items-center gap-2">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:16px;height:16px;flex-shrink:0;display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Berlaku hingga {{ $rec->expires_at->translatedFormat('d MMMM Y') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Body --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
            <div class="prose prose-slate prose-lg dark:prose-invert max-w-none leading-relaxed">
                {!! $rec->body !!}
            </div>
        </div>

        {{-- Attachments --}}
        @if(is_array($rec->attachments) && count($rec->attachments) > 0)
            @php
                $fileTypes = [
                    'pdf' => ['label' => 'PDF', 'bg' => 'bg-red-50 dark:bg-red-500/10', 'text' => 'text-red-600 dark:text-red-400', 'ring' => 'ring-red-200 dark:ring-red-500/20', 'icon' => 'red'],
                    'doc' => ['label' => 'DOC', 'bg' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'ring' => 'ring-blue-200 dark:ring-blue-500/20', 'icon' => 'blue'],
                    'docx' => ['label' => 'DOC', 'bg' => 'bg-blue-50 dark:bg-blue-500/10', 'text' => 'text-blue-600 dark:text-blue-400', 'ring' => 'ring-blue-200 dark:ring-blue-500/20', 'icon' => 'blue'],
                    'xls' => ['label' => 'XLS', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'ring' => 'ring-emerald-200 dark:ring-emerald-500/20', 'icon' => 'emerald'],
                    'xlsx' => ['label' => 'XLS', 'bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'text' => 'text-emerald-600 dark:text-emerald-400', 'ring' => 'ring-emerald-200 dark:ring-emerald-500/20', 'icon' => 'emerald'],
                    'jpg' => ['label' => 'IMG', 'bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400', 'ring' => 'ring-purple-200 dark:ring-purple-500/20', 'icon' => 'purple'],
                    'jpeg' => ['label' => 'IMG', 'bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400', 'ring' => 'ring-purple-200 dark:ring-purple-500/20', 'icon' => 'purple'],
                    'png' => ['label' => 'IMG', 'bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400', 'ring' => 'ring-purple-200 dark:ring-purple-500/20', 'icon' => 'purple'],
                    'webp' => ['label' => 'IMG', 'bg' => 'bg-purple-50 dark:bg-purple-500/10', 'text' => 'text-purple-600 dark:text-purple-400', 'ring' => 'ring-purple-200 dark:ring-purple-500/20', 'icon' => 'purple'],
                ];
            @endphp
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 flex items-center gap-2">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;display:inline-block;" class="text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                        Lampiran
                    </h3>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ count($rec->attachments) }} berkas</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($rec->attachments as $path)
                        @php
                            $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                            $name = basename($path);
                            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                            $type = $fileTypes[$ext] ?? ['label' => strtoupper($ext ?: 'FILE'), 'bg' => 'bg-gray-100 dark:bg-gray-800', 'text' => 'text-gray-600 dark:text-gray-300', 'ring' => 'ring-gray-200 dark:ring-gray-700', 'icon' => 'gray'];
                            $size = null;
                            try {
                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
                                    $bytes = \Illuminate\Support\Facades\Storage::disk('public')->size($path);
                                    $size = $bytes >= 1048576 ? number_format($bytes / 1048576, 1) . ' MB' : number_format($bytes / 1024, 0) . ' KB';
                                }
                            } catch (\Throwable $e) { $size = null; }
                        @endphp
                        <a href="{{ $url }}" target="_blank" rel="noopener"
                           class="group flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 hover:border-primary-400 hover:shadow-sm hover:bg-gray-50 dark:hover:bg-gray-800/60 transition-all duration-200">
                            <span class="flex-shrink-0 inline-flex items-center justify-center w-10 h-10 rounded-lg ring-1 {{ $type['bg'] }} {{ $type['ring'] }}">
                                <span class="text-[10px] font-bold tracking-wide {{ $type['text'] }}">{{ $type['label'] }}</span>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate" title="{{ $name }}">{{ $name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $size ?? 'Berkas' }}</div>
                            </div>
                            <span class="flex-shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 group-hover:text-primary-700 px-2 py-1 rounded-md bg-primary-50 dark:bg-primary-500/10" style="line-height:1;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:14px;height:14px;flex-shrink:0;display:inline-block;"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                <span>Unduh</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Targets (admin view) --}}
        @if($user?->hasAnyRole(['super_admin', 'admin', 'editor']))
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-50 mb-5 flex items-center gap-2">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width:20px;height:20px;flex-shrink:0;display:inline-block;" class="text-gray-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Target & Acknowledgement
                </h3>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Target Role</div>
                        <div class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $rec->target_roles ? collect($rec->target_roles)->map(fn($r) => \App\Models\InternalAnnouncement::TARGET_ROLES[$r] ?? $r)->join(', ') : 'Semua role internal' }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-4">
                        <div class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">Sudah Membaca</div>
                        <div class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ $rec->acknowledgements()->count() }} orang</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
