<div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/60 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
    <div class="flex items-center gap-3 mb-6">
        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 shadow-sm text-slate-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900 leading-tight">Keamanan Akun</h2>
            <p class="text-sm text-slate-500">Perbarui password untuk menjaga keamanan akun Anda.</p>
        </div>
    </div>

    @if(session('success_password'))
        <div class="mb-6 p-4 rounded-xl bg-[#F0FDF4] border border-[#BBF7D0] flex gap-3 text-[#166534] shadow-sm animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0 text-[#16A34A]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span class="text-sm font-medium leading-relaxed">{{ session('success_password') }}</span>
        </div>
    @endif

    <form wire:submit="updatePassword" class="space-y-5">
        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-slate-700">Password Saat Ini</label>
            <input 
                type="password" 
                wire:model="current_password" 
                placeholder="Masukkan password Anda saat ini"
                class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-200 outline-none placeholder:text-slate-400"
            >
            @error('current_password') <p class="text-xs font-medium text-red-500 flex items-center gap-1 mt-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</p> @enderror
        </div>

        <div class="h-px bg-slate-100 my-2"></div>

        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-slate-700">Password Baru</label>
            <input 
                type="password" 
                wire:model="password" 
                placeholder="Ketik password baru Anda"
                class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-200 outline-none placeholder:text-slate-400"
            >
            @error('password') <p class="text-xs font-medium text-red-500 flex items-center gap-1 mt-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg> {{ $message }}</p> @enderror
        </div>

        <div class="space-y-1.5">
            <label class="block text-sm font-semibold text-slate-700">Konfirmasi Password</label>
            <input 
                type="password" 
                wire:model="password_confirmation" 
                placeholder="Ulangi password baru Anda"
                class="w-full px-4 py-2.5 bg-slate-50/50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-600 focus:bg-white transition-all duration-200 outline-none placeholder:text-slate-400"
            >
        </div>

        <div class="pt-3">
            <button 
                type="submit" 
                class="w-full sm:w-auto px-6 py-2.5 bg-[#0F172A] hover:bg-[#1E293B] text-white text-sm font-semibold rounded-xl shadow-[0_4px_12px_-3px_rgba(15,23,42,0.3)] hover:shadow-none hover:translate-y-px transition-all duration-200 flex items-center justify-center gap-2 relative overflow-hidden group"
            >
                <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                <span class="relative z-10 flex items-center gap-2">
                    <span wire:loading.remove wire:target="updatePassword">Simpan Password</span>
                    <span wire:loading wire:target="updatePassword">Menyimpan...</span>
                </span>
            </button>
        </div>
    </form>
    
    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in 0.3s ease-out forwards;
        }
    </style>
</div>
