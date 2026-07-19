<div class="p-6">
    <div class="flex items-center mb-6">
        <a href="{{ route('mobile.dashboard') }}" wire:navigate class="mr-4 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <h1 class="text-xl font-bold text-gray-900">Sesi Mengajar</h1>
    </div>

    @if($sessions->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($sessions as $session)
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-50 text-primary-700 mb-2">
                                {{ $session->schoolClass->name ?? 'Kelas Tidak Ditemukan' }}
                            </span>
                            <h3 class="text-base font-bold text-gray-900">{{ $session->subject->name ?? 'Mata Pelajaran Tidak Ditemukan' }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-1">{{ $session->topic ?? 'Tidak ada topik' }}</p>
                        </div>
                        <div class="text-right">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-800',
                                    'published' => 'bg-blue-100 text-blue-800',
                                    'ongoing' => 'bg-yellow-100 text-yellow-800',
                                    'completed' => 'bg-green-100 text-green-800',
                                ];
                                $colorClass = $statusColors[$session->status] ?? 'bg-gray-100 text-gray-800';
                                
                                $statusLabels = [
                                    'draft' => 'Draft',
                                    'published' => 'Terjadwal',
                                    'ongoing' => 'Berlangsung',
                                    'completed' => 'Selesai',
                                ];
                                $statusLabel = $statusLabels[$session->status] ?? ucfirst($session->status);
                            @endphp
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $colorClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center text-xs text-gray-500 mt-3 pt-3 border-t border-gray-50">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span>{{ $session->session_date ? $session->session_date->format('d M Y') : '-' }}</span>
                        
                        <span class="mx-2">•</span>
                        
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(method_exists($sessions, 'links'))
            <div class="mt-4 pb-4">
                {{ $sessions->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-primary-100 text-primary-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Sesi</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum memiliki jadwal mengajar.</p>
        </div>
    @endif
</div>
