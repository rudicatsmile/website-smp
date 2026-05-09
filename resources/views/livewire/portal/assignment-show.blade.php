<div class="max-w-4xl mx-auto space-y-6">
    <a href="{{ route('portal.assignments.index') }}" class="inline-flex items-center gap-1 text-sm text-slate-600 hover:text-emerald-600 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke daftar tugas
    </a>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4">{{ session('success') }}</div>
    @endif

    {{-- Header --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-semibold">{{ $assignment->subject?->name ?? 'Umum' }}</span>
            <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-semibold">Kelas {{ $assignment->schoolClass?->name }}</span>
            @if($assignment->is_overdue && !$submission?->submitted_at)
                <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-semibold">Terlambat</span>
            @endif
        </div>
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 leading-tight">{{ $assignment->title }}</h1>
        <div class="flex flex-wrap gap-4 mt-4 pt-4 border-t border-slate-100 text-sm text-slate-600">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                {{ $assignment->teacher?->name ?? '—' }}
            </div>
            @if($assignment->due_at)
                <div class="flex items-center gap-2 {{ $assignment->is_overdue && !$submission?->submitted_at ? 'text-red-600 font-semibold' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Deadline: {{ $assignment->due_at->translatedFormat('d M Y, H:i') }}
                </div>
            @endif
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                Skor maks: {{ $assignment->max_score }}
            </div>
        </div>
    </div>

    {{-- Deskripsi --}}
    @if($assignment->description)
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-800 mb-3">Deskripsi Tugas</h2>
            <div class="prose prose-slate max-w-none">{!! $assignment->description !!}</div>
        </div>
    @endif

    {{-- Lampiran guru --}}
    @if(is_array($assignment->attachments) && count($assignment->attachments) > 0)
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
            <h2 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24" class="text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                Lampiran dari Guru
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($assignment->attachments as $path)
                    @php
                        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($path);
                        $name = basename($path);
                        $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION));
                    @endphp
                    <a href="{{ $url }}" target="_blank" class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition">
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $ext ?: 'FILE' }}</div>
                        <div class="flex-1 min-w-0">
                            <div class="text-sm font-medium text-slate-800 truncate">{{ $name }}</div>
                            <div class="text-xs text-emerald-600 font-semibold">Unduh →</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Submission --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h2 class="font-bold text-slate-800 mb-4">Pengumpulan Tugas</h2>

        @if($submission && $submission->score !== null)
            {{-- Graded --}}
            <div class="bg-gradient-to-br from-sky-50 to-indigo-50 border border-sky-200 rounded-xl p-5 mb-4">
                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div>
                        <div class="text-xs text-sky-700 font-semibold uppercase">Nilai Anda</div>
                        <div class="text-4xl font-extrabold text-sky-700 mt-1">{{ $submission->score }}<span class="text-xl text-sky-500">/{{ $assignment->max_score }}</span></div>
                    </div>
                    <div class="text-right text-xs text-slate-600">
                        Dinilai: {{ $submission->graded_at?->translatedFormat('d M Y, H:i') ?? '—' }}
                    </div>
                </div>
                @if($submission->feedback)
                    <div class="mt-4 pt-4 border-t border-sky-200">
                        <div class="text-xs text-sky-700 font-semibold uppercase mb-1">Feedback Guru</div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $submission->feedback }}</p>
                    </div>
                @endif
            </div>
        @endif

        @if($submission && $submission->submitted_at)
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-4">
                <div class="flex items-center gap-2 text-emerald-700 font-semibold text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Dikumpulkan pada {{ $submission->submitted_at->translatedFormat('d M Y, H:i') }}
                    @if($submission->is_late)
                        <span class="px-2 py-0.5 rounded bg-red-100 text-red-700 text-xs">Terlambat</span>
                    @endif
                </div>
                @if(is_array($submission->files) && count($submission->files) > 0)
                    <div class="mt-3 space-y-1">
                        @foreach($submission->files as $path)
                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($path) }}" target="_blank" class="block text-sm text-emerald-700 hover:underline">
                                📄 {{ basename($path) }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        @if(!$submission || !$submission->submitted_at || $submission->score === null)
            {{-- Upload form (boleh update submit selama belum dinilai) --}}
            <form wire:submit="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Upload Berkas Jawaban</label>
                    <input type="file" wire:model="files" multiple
                           class="block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    <p class="text-xs text-slate-500 mt-1">Maks 10MB per file. Format: PDF, DOC, DOCX, JPG, PNG, ZIP.</p>
                    @error('files.*') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Catatan (opsional)</label>
                    <textarea wire:model="note" rows="3" class="w-full px-3 py-2 rounded-lg border border-slate-200 focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none text-sm"></textarea>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">
                    <svg wire:loading.remove class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg wire:loading class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"/><path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" class="opacity-75"/></svg>
                    {{ $submission?->submitted_at ? 'Kumpulkan Ulang' : 'Kumpulkan Tugas' }}
                </button>
            </form>
        @endif
    </div>
</div>
