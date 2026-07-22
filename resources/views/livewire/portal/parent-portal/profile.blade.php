<div class="max-w-3xl mx-auto space-y-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Profil Saya</h1>

    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="w-20 h-20 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-3xl font-bold">
                {{ substr($user->name, 0, 1) }}
            </div>
            <div>
                <div class="text-xl font-bold text-slate-800">{{ $user->name }}</div>
                <div class="text-sm text-slate-500">{{ $user->email }}</div>
                <div class="mt-1 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    Akun Orang Tua
                </div>
            </div>
        </div>
    </div>

    <livewire:shared.change-password />
</div>
