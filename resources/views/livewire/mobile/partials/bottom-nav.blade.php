<!-- Bottom Navigation Bar -->
<div class="fixed bottom-0 w-full max-w-md bg-white/80 backdrop-blur-md border-t border-white/50 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)] z-50 transition-all duration-300">
    <div class="flex justify-around items-center h-16 px-2">
        <!-- Dashboard -->
        <a href="{{ route('mobile.dashboard') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('mobile.dashboard') ? 'text-primary-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-[10px] tracking-wide uppercase">Beranda</span>
        </a>

        <!-- Lesson Plans -->
        <a href="{{ route('mobile.plans') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('mobile.plans') || request()->routeIs('mobile.plans.show') ? 'text-primary-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-[10px] tracking-wide uppercase">Rencana</span>
        </a>

        <!-- Teaching Sessions -->
        <a href="{{ route('mobile.sessions') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('mobile.sessions') ? 'text-primary-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-[10px] tracking-wide uppercase">Sesi</span>
        </a>

        <!-- Profil -->
        <a href="{{ route('mobile.profile') }}" wire:navigate class="flex flex-col items-center justify-center w-full h-full transition-colors {{ request()->routeIs('mobile.profile') ? 'text-primary-600 font-bold' : 'text-gray-400 hover:text-gray-600' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-[10px] tracking-wide uppercase">Profil</span>
        </a>
    </div>
</div>
