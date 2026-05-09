<div>
    <x-site.page-hero key="staff" title="Profil Guru & Staf" :subtitle="$member->name" icon="heroicon-o-user" />

    <x-site.page-frame>
        <div class="space-y-8">
        <!-- Hero Section -->
        <div class="bg-white rounded-2xl shadow-sm overflow-hidden border border-slate-100">
            <div class="md:flex">
                <div class="md:w-1/3 bg-gradient-to-br from-emerald-500 to-teal-600 p-8 flex items-center justify-center">
                    <div class="w-48 h-48 rounded-full bg-white/20 flex items-center justify-center overflow-hidden border-4 border-white/30">
                        @if($member->photo)
                            <img src="{{ $member->photo_url }}" alt="{{ $member->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-6xl font-bold text-white">{{ substr($member->name, 0, 1) }}</span>
                        @endif
                    </div>
                </div>
                <div class="md:w-2/3 p-8">
                    @if($member->category)
                        <div class="inline-block px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-sm font-medium mb-4">
                            {{ $member->category->name }}
                        </div>
                    @endif
                    <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $member->name }}</h1>
                    @if($member->position)
                        <p class="text-lg text-slate-600 mb-4">{{ $member->position }}</p>
                    @endif
                    @if($member->quote)
                        <div class="bg-slate-50 rounded-xl p-4 italic text-slate-700 border-l-4 border-emerald-500">
                            "{{ $member->quote }}"
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex border-b border-slate-200">
                <button
                    wire:click="setActiveTab('bio')"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $activeTab === 'bio' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-slate-600 hover:text-slate-800' }}"
                >
                    Biografi
                </button>
                <button
                    wire:click="setActiveTab('education')"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $activeTab === 'education' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-slate-600 hover:text-slate-800' }}"
                >
                    Pendidikan
                </button>
                <button
                    wire:click="setActiveTab('certifications')"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $activeTab === 'certifications' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-slate-600 hover:text-slate-800' }}"
                >
                    Sertifikasi
                </button>
                <button
                    wire:click="setActiveTab('experience')"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $activeTab === 'experience' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-slate-600 hover:text-slate-800' }}"
                >
                    Pengalaman
                </button>
                <button
                    wire:click="setActiveTab('contact')"
                    class="flex-1 px-6 py-4 text-center font-medium transition {{ $activeTab === 'contact' ? 'text-emerald-600 border-b-2 border-emerald-600 bg-emerald-50' : 'text-slate-600 hover:text-slate-800' }}"
                >
                    Kontak
                </button>
            </div>

            <div class="p-8">
                <!-- Bio Tab -->
                @if($activeTab === 'bio')
                    <div class="space-y-6">
                        @if($member->bio)
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Tentang</h3>
                                <p class="text-slate-600 leading-relaxed">{{ $member->bio }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @if($member->nip)
                                <div class="bg-slate-50 rounded-xl p-4 text-center">
                                    <div class="text-sm text-slate-500 mb-1">NIP</div>
                                    <div class="font-semibold text-slate-800">{{ $member->nip }}</div>
                                </div>
                            @endif
                            @if($member->nuptk)
                                <div class="bg-slate-50 rounded-xl p-4 text-center">
                                    <div class="text-sm text-slate-500 mb-1">NUPTK</div>
                                    <div class="font-semibold text-slate-800">{{ $member->nuptk }}</div>
                                </div>
                            @endif
                            @if($member->gender)
                                <div class="bg-slate-50 rounded-xl p-4 text-center">
                                    <div class="text-sm text-slate-500 mb-1">Jenis Kelamin</div>
                                    <div class="font-semibold text-slate-800">{{ $member->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </div>
                            @endif
                            @if($member->years_of_service)
                                <div class="bg-slate-50 rounded-xl p-4 text-center">
                                    <div class="text-sm text-slate-500 mb-1">Masa Kerja</div>
                                    <div class="font-semibold text-slate-800">{{ $member->years_of_service }} Tahun</div>
                                </div>
                            @endif
                        </div>

                        @if($member->subjects && count($member->subjects) > 0)
                            <div>
                                <h3 class="text-lg font-semibold text-slate-800 mb-3">Mata Pelajaran</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($member->subjects as $subject)
                                        <span class="px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-medium">{{ is_array($subject) ? ($subject['subject'] ?? '') : $subject }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Education Tab -->
                @if($activeTab === 'education')
                    @if($member->education && count($member->education) > 0)
                        <div class="space-y-4">
                            @foreach($member->education as $edu)
                                <div class="bg-slate-50 rounded-xl p-6 border-l-4 border-emerald-500">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-lg font-semibold text-slate-800">{{ $edu['degree'] ?? '' }}</h4>
                                        @if(isset($edu['year']))
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-sm font-medium">{{ $edu['year'] }}</span>
                                        @endif
                                    </div>
                                    <p class="text-slate-700 mb-1">{{ $edu['major'] ?? '' }}</p>
                                    <p class="text-sm text-slate-500">{{ $edu['institution'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">Belum ada data pendidikan</div>
                    @endif
                @endif

                <!-- Certifications Tab -->
                @if($activeTab === 'certifications')
                    @if($member->certifications && count($member->certifications) > 0)
                        <div class="space-y-4">
                            @foreach($member->certifications as $cert)
                                <div class="bg-slate-50 rounded-xl p-6 border-l-4 border-teal-500">
                                    <div class="flex items-start justify-between mb-2">
                                        <h4 class="text-lg font-semibold text-slate-800">{{ $cert['name'] ?? '' }}</h4>
                                        @if(isset($cert['year']))
                                            <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-sm font-medium">{{ $cert['year'] }}</span>
                                        @endif
                                    </div>
                                    @if(isset($cert['issuer']))
                                        <p class="text-sm text-slate-500">Penerbit: {{ $cert['issuer'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">Belum ada data sertifikasi</div>
                    @endif
                @endif

                <!-- Experience Tab -->
                @if($activeTab === 'experience')
                    @if($member->experiences && count($member->experiences) > 0)
                        <div class="space-y-4">
                            @foreach($member->experiences as $exp)
                                <div class="bg-slate-50 rounded-xl p-6 border-l-4 border-blue-500">
                                    <h4 class="text-lg font-semibold text-slate-800 mb-2">{{ $exp['position'] ?? '' }}</h4>
                                    <p class="text-slate-700 mb-1">{{ $exp['organization'] ?? '' }}</p>
                                    <p class="text-sm text-slate-500">
                                        @if(isset($exp['start_year']))
                                            {{ $exp['start_year'] }}
                                            @if(isset($exp['end_year']) && $exp['end_year'])
                                                - {{ $exp['end_year'] }}
                                            @else
                                                - Sekarang
                                            @endif
                                        @endif
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-slate-500">Belum ada data pengalaman kerja</div>
                    @endif
                @endif

                <!-- Contact Tab -->
                @if($activeTab === 'contact')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($member->email)
                            <div class="bg-slate-50 rounded-xl p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-500 mb-1">Email</div>
                                        <a href="mailto:{{ $member->email }}" class="text-emerald-600 font-medium hover:underline">{{ $member->email }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->phone)
                            <div class="bg-slate-50 rounded-xl p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-500 mb-1">Telepon</div>
                                        <a href="tel:{{ $member->phone }}" class="text-emerald-600 font-medium hover:underline">{{ $member->phone }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->whatsapp)
                            <div class="bg-slate-50 rounded-xl p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm text-slate-500 mb-1">WhatsApp</div>
                                        <a href="https://wa.me/{{ $member->whatsapp }}" target="_blank" class="text-emerald-600 font-medium hover:underline">{{ $member->whatsapp }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($member->social && count($member->social) > 0)
                            <div class="bg-slate-50 rounded-xl p-6 md:col-span-2">
                                <h4 class="text-sm text-slate-500 mb-4">Media Sosial</h4>
                                <div class="flex flex-wrap gap-3">
                                    @foreach($member->social as $social)
                                        @if(isset($social['platform']) && isset($social['url']))
                                            <a href="{{ $social['url'] }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-700 hover:bg-slate-50 transition">
                                                {{ $social['platform'] }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <!-- Jadwal Mingguan -->
        @if($member->schedules->count() > 0)
            @php
                $scheduleByDay = $member->schedules->groupBy('day_of_week');
                $orderedDays = [1, 2, 3, 4, 5, 6, 0];
                $typeBadge = [
                    'mengajar' => 'bg-emerald-100 text-emerald-700',
                    'piket' => 'bg-amber-100 text-amber-700',
                    'rapat' => 'bg-sky-100 text-sky-700',
                    'lainnya' => 'bg-slate-100 text-slate-700',
                ];
            @endphp
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Jadwal Mingguan
                    </h3>
                    <a href="{{ route('jadwal.index', ['staffId' => $member->id]) }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat Jadwal Lengkap &rarr;</a>
                </div>
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 divide-y divide-slate-100">
                    @foreach($orderedDays as $d)
                        @php $items = ($scheduleByDay->get($d, collect()))->sortBy('start_time'); @endphp
                        @if($items->count() > 0)
                            <div class="p-4 md:p-5 grid grid-cols-1 md:grid-cols-12 gap-3">
                                <div class="md:col-span-2 font-bold text-slate-700">{{ \App\Models\StaffSchedule::DAYS[$d] }}</div>
                                <div class="md:col-span-10 space-y-2">
                                    @foreach($items as $item)
                                        <div class="flex flex-wrap items-center gap-2 text-sm">
                                            <span class="px-2 py-0.5 rounded text-xs font-bold {{ $typeBadge[$item->type] ?? 'bg-slate-100 text-slate-700' }}">{{ $item->type_label }}</span>
                                            <span class="font-mono text-slate-600">{{ $item->time_range }}</span>
                                            <span class="text-slate-800 font-medium">{{ $item->display_title }}</span>
                                            @if($item->location)
                                                <span class="text-xs text-slate-500">di {{ $item->location }}</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Staff -->
        @if($relatedStaff->count() > 0)
            <div>
                <h3 class="text-xl font-bold text-slate-800 mb-6">Staf Lainnya</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedStaff as $staff)
                        <div class="bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-slate-100 group">
                            <div class="relative h-32 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                @if($staff->photo)
                                    <img src="{{ $staff->photo_url }}" alt="{{ $staff->name }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-4xl font-bold text-slate-400">{{ substr($staff->name, 0, 1) }}</span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h4 class="text-sm font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition truncate">{{ $staff->name }}</h4>
                                @if($staff->position)
                                    <p class="text-xs text-slate-600 mb-2 truncate">{{ $staff->position }}</p>
                                @endif
                                <a href="{{ route('staff.show', $staff->slug) }}" class="text-xs text-emerald-600 font-medium hover:text-emerald-700 transition">
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-site.page-frame>
</div>
