<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Profil Saya</h1>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-4 mb-6">
            @if($student?->photo_url)
                <img src="{{ $student->photo_url }}" class="w-20 h-20 rounded-full object-cover" alt="">
            @else
                <div class="w-20 h-20 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-3xl font-bold">{{ substr($user->name, 0, 1) }}</div>
            @endif
            <div>
                <div class="text-xl font-bold text-slate-800">{{ $user->name }}</div>
                <div class="text-sm text-slate-500">{{ $user->email }}</div>
            </div>
        </div>

        @if($student)
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div><dt class="text-xs text-slate-500">NIS</dt><dd class="font-semibold">{{ $student->nis }}</dd></div>
                <div><dt class="text-xs text-slate-500">NISN</dt><dd class="font-semibold">{{ $student->nisn ?? '—' }}</dd></div>
                <div><dt class="text-xs text-slate-500">Kelas</dt><dd class="font-semibold">{{ $student->schoolClass?->name ?? '—' }}</dd></div>
                <div><dt class="text-xs text-slate-500">Jenis Kelamin</dt><dd class="font-semibold">{{ \App\Models\Student::GENDERS[$student->gender] ?? '—' }}</dd></div>
                <div><dt class="text-xs text-slate-500">TTL</dt><dd class="font-semibold">{{ $student->birth_place }}{{ $student->birth_date ? ', '.$student->birth_date->translatedFormat('d M Y') : '' }}</dd></div>
                <div><dt class="text-xs text-slate-500">Orang Tua</dt><dd class="font-semibold">{{ $student->parent_name ?? '—' }}</dd></div>
            </dl>
        @endif
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <h2 class="font-bold text-slate-800 mb-4">Ganti Password</h2>
        <form wire:submit="updatePassword" class="space-y-4 max-w-md">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password Saat Ini</label>
                <input type="password" wire:model="current_password" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                <input type="password" wire:model="password" class="w-full px-3 py-2 rounded-lg border border-slate-200">
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password</label>
                <input type="password" wire:model="password_confirmation" class="w-full px-3 py-2 rounded-lg border border-slate-200">
            </div>
            <button type="submit" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition">Simpan</button>
        </form>
    </div>
</div>
