<div class="p-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ explode(' ', $user->name)[0] }}!</h1>
            <p class="text-sm text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
        <div>
            <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-700 font-bold border border-primary-200">
                {{ substr($user->name, 0, 1) }}
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <div class="text-gray-400 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $todaySessionsCount }}</span>
            <span class="text-xs text-gray-500 font-medium">Sesi Hari Ini</span>
        </div>
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
            <div class="text-gray-400 mb-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-2xl font-bold text-gray-900">{{ $pendingSessionsCount }}</span>
            <span class="text-xs text-gray-500 font-medium">Sesi Aktif/Tertunda</span>
        </div>
    </div>

    <!-- Quick Actions -->
    <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi Cepat</h2>
    <div class="grid grid-cols-2 gap-4 mb-8">
        <a href="{{ route('mobile.sessions') }}" wire:navigate class="bg-primary-50 p-4 rounded-2xl border border-primary-100 flex flex-col items-center justify-center text-center">
            <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-primary-600 shadow-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <span class="text-sm font-semibold text-primary-900">Mulai Sesi</span>
        </a>
        <a href="{{ route('mobile.plans') }}" wire:navigate class="bg-orange-50 p-4 rounded-2xl border border-orange-100 flex flex-col items-center justify-center text-center">
            <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center text-orange-600 shadow-sm mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
            </div>
            <span class="text-sm font-semibold text-orange-900">Buat Rencana</span>
        </a>
    </div>

    <!-- Account Actions -->
    <div class="mt-8 pt-6 border-t border-gray-100">
        <button wire:click="logout" class="flex w-full items-center text-red-600 font-medium py-2">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
            </svg>
            Keluar dari Aplikasi
        </button>
    </div>
</div>
