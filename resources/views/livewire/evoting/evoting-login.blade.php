<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-16 h-16 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: #ffffff;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-slate-900">
            E-Voting OSIS
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            SMP Al Wathoniyah 9
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-slate-100">
            <form wire:submit="login" class="space-y-6">
                <div>
                    <label for="nisn" class="block text-sm font-medium text-slate-700">
                        NISN
                    </label>
                    <div class="mt-1">
                        <input wire:model="nisn" id="nisn" name="nisn" type="text" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors duration-200" placeholder="Masukkan NISN Anda">
                    </div>
                    @error('nisn') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="token" class="block text-sm font-medium text-slate-700">
                        Token Pemilihan
                    </label>
                    <div class="mt-1">
                        <input wire:model="token" id="token" name="token" type="text" required class="appearance-none block w-full px-3 py-3 border border-slate-300 rounded-xl shadow-sm placeholder-slate-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm uppercase transition-colors duration-200" placeholder="Contoh: A8X2B">
                    </div>
                    @error('token') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-bold transition-all duration-200 active:scale-95" style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: #ffffff; box-shadow: 0 4px 15px rgba(79, 70, 229, 0.4);">
                        Masuk & Berikan Suara
                    </button>
                </div>
            </form>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-slate-500">
                    Sistem pemilihan ini menjamin kerahasiaan pilihan Anda (Secret Ballot).
                </p>
            </div>
        </div>
    </div>
</div>
