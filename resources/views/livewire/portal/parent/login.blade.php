<div class="w-full max-w-md">
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <div class="text-center mb-6">
            @php $settings = app(\App\Settings\GeneralSettings::class); @endphp
            @if($settings->logo)
                <img src="{{ asset('storage/'.$settings->logo) }}" class="w-16 h-16 mx-auto rounded-xl mb-3">
            @else
                <div class="w-16 h-16 mx-auto rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold mb-3">O</div>
            @endif
            <h1 class="text-2xl font-extrabold text-slate-800">Portal Orang Tua</h1>
            <p class="text-sm text-slate-500">{{ $settings->school_name }}</p>
        </div>

        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                <input type="email" wire:model="email" autofocus autocomplete="email"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <input type="password" wire:model="password" autocomplete="current-password"
                       class="w-full px-4 py-2.5 rounded-lg border-2 border-slate-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100 outline-none">
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" wire:model="remember" class="rounded text-indigo-600">
                Ingat saya
            </label>
            <button type="submit" class="w-full py-2.5 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 text-white font-semibold hover:from-indigo-700 hover:to-blue-700 transition">
                Masuk
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-slate-100 text-center text-xs text-slate-500 space-y-2">
            <div><a href="{{ route('portal.login') }}" class="hover:text-indigo-600">Portal Siswa &rarr;</a></div>
            <div><a href="{{ route('home') }}" class="hover:text-indigo-600">&larr; Kembali ke beranda</a></div>
        </div>
    </div>
</div>
