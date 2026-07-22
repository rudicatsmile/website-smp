<div class="space-y-6 pb-24">
    <!-- Header profil -->
    <div class="bg-white p-6 rounded-b-[2rem] shadow-sm mb-4">
        <div class="flex flex-col items-center text-center">
            <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-4xl font-bold mb-4 shadow-inner">
                {{ substr($user->name, 0, 1) }}
            </div>
            <h1 class="text-xl font-bold text-slate-800">{{ $user->name }}</h1>
            <p class="text-sm text-slate-500 mb-2">{{ $user->email }}</p>
            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-100">
                Akun Guru
            </div>
        </div>
    </div>

    <div class="px-4">
        <livewire:shared.change-password />
    </div>
</div>
