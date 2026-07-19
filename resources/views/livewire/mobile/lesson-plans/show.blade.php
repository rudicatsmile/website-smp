<div class="p-6 pb-24 relative min-h-screen" x-data="{
    notification: '',
    showNotif: false,
    init() {
        window.addEventListener('show-notification', () => {
            this.notification = @this.notification;
            this.showNotif = true;
            setTimeout(() => this.showNotif = false, 3000);
        });
    }
}">
    <!-- Header with Back Button -->
    <div class="flex items-center mb-6">
        <a href="{{ route('mobile.plans') }}" wire:navigate class="mr-4 p-2 bg-white rounded-full shadow-sm text-gray-500 hover:text-gray-900 active:scale-95 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-gray-900 leading-tight">{{ $plan->title }}</h1>
            <p class="text-sm text-gray-500">{{ $plan->subject->name ?? 'Mapel' }} &bull; {{ $plan->schoolClass->name ?? 'Kelas' }}</p>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex space-x-3 mb-6">
        <button wire:click="openTopicModal" class="flex-1 bg-white border border-gray-200 text-gray-700 font-semibold py-2.5 px-4 rounded-xl shadow-sm hover:bg-gray-50 flex items-center justify-center space-x-2 active:scale-[0.98] transition-transform">
            <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="text-sm">Tambah Pertemuan</span>
        </button>
        
        @if($plan->topics->count() > 0)
        <button wire:click="openApplyModal" class="flex-1 bg-primary-600 text-white font-semibold py-2.5 px-4 rounded-xl shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:bg-primary-700 flex items-center justify-center space-x-2 active:scale-[0.98] transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="text-sm">Apply ke Tanggal</span>
        </button>
        @endif
    </div>

    <!-- Topics List -->
    <div class="space-y-4">
        @forelse($plan->topics as $topic)
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col relative overflow-hidden">
                <div class="absolute top-0 left-0 w-1 h-full bg-primary-500"></div>
                <div class="flex justify-between items-start mb-2 pl-2">
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-primary-50 text-primary-700">
                                Minggu {{ $topic->week_number }}
                            </span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-700">
                                Pert. {{ $topic->order }}
                            </span>
                        </div>
                        <h3 class="text-sm font-bold text-gray-900 leading-snug">{{ $topic->topic }}</h3>
                    </div>
                    <div class="flex space-x-1 ml-3">
                        <button wire:click="editTopic({{ $topic->id }})" class="p-1.5 text-gray-400 hover:text-blue-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                        </button>
                        <button wire:click="deleteTopic({{ $topic->id }})" wire:confirm="Hapus topik ini?" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
                <div class="pl-2 flex items-center text-xs text-gray-500 mt-2">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $topic->default_duration_minutes }} menit
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white/50 rounded-2xl border border-gray-100 border-dashed">
                <p class="text-sm text-gray-500">Belum ada topik pertemuan yang ditambahkan.</p>
            </div>
        @endforelse
    </div>

    <!-- Topic Modal (Slide-over) -->
    <div x-data="{ open: @entangle('isTopicModalOpen') }" 
         x-show="open" 
         class="fixed inset-0 z-[100] overflow-hidden" 
         style="display: none;">
        
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
             x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="open = false" wire:click="$set('isTopicModalOpen', false)"></div>
        
        <div class="fixed inset-y-0 right-0 flex max-w-full sm:pl-16">
            <div x-show="open"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col h-full">
                
                <div class="px-4 py-6 bg-white border-b border-gray-100 sm:px-6 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ $topicModalMode === 'create' ? 'Tambah Pertemuan' : 'Edit Pertemuan' }}
                    </h2>
                    <button @click="open = false" wire:click="$set('isTopicModalOpen', false)" class="text-gray-400 hover:text-gray-500 bg-gray-50 p-2 rounded-full">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 bg-gray-50/50">
                    <form id="topicForm" wire:submit.prevent="saveTopic" class="space-y-5">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Minggu Ke- <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="week_number" min="1" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                @error('week_number') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Pertemuan Ke- <span class="text-red-500">*</span></label>
                                <input type="number" wire:model="order" min="1" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                @error('order') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Topik / Bab <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="topic" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            @error('topic') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Durasi (Menit) <span class="text-red-500">*</span></label>
                            <input type="number" wire:model="default_duration_minutes" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            @error('default_duration_minutes') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>
                        
                        @if(!empty($availableObjectives))
                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Tujuan Pembelajaran</h4>
                            <div class="max-h-32 overflow-y-auto space-y-2">
                                @foreach($availableObjectives as $id => $name)
                                <label class="flex items-start cursor-pointer group">
                                    <div class="flex h-6 items-center">
                                        <input wire:model="learning_objectives" value="{{ $id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                    </div>
                                    <div class="ml-3 text-sm leading-6 text-gray-700 group-hover:text-gray-900">
                                        {{ $name }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Alur Tujuan Pembelajaran (Repeater) -->
                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                            <div class="flex justify-between items-center mb-3">
                                <h4 class="text-sm font-semibold text-gray-900">Alur Tujuan Pembelajaran</h4>
                                <button type="button" wire:click="addLearningPath" class="text-xs font-semibold text-primary-600 bg-primary-50 px-2 py-1 rounded-md hover:bg-primary-100">
                                    + Tambah ATP
                                </button>
                            </div>
                            
                            <div class="space-y-3">
                                @foreach($learning_paths as $index => $path)
                                <div class="p-3 border border-gray-200 rounded-lg bg-gray-50/50 relative">
                                    <button type="button" wire:click="removeLearningPath({{ $index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-white rounded-full p-0.5 shadow-sm">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                    
                                    <div class="pr-6 space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Deskripsi ATP <span class="text-red-500">*</span></label>
                                            <input type="text" wire:model="learning_paths.{{ $index }}.description" required class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-xs sm:leading-6">
                                            @error('learning_paths.'.$index.'.description') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700">Level KKO <span class="text-red-500">*</span></label>
                                            <select wire:model="learning_paths.{{ $index }}.kko_level_id" required class="mt-1 block w-full rounded-md border-0 py-1.5 pl-3 pr-8 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-xs sm:leading-6">
                                                <option value="">-- Pilih --</option>
                                                @foreach($kkoLevels ?? [] as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                            @error('learning_paths.'.$index.'.kko_level_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                
                                @if(empty($learning_paths))
                                    <div class="text-center py-4 border border-dashed border-gray-300 rounded-lg">
                                        <p class="text-xs text-gray-500">Belum ada Alur Tujuan Pembelajaran ditambahkan.</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Metode & Media -->
                        <div class="grid grid-cols-1 gap-4">
                            @if(!empty($availableMethods))
                            <div class="bg-white p-4 rounded-xl border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Metode Pembelajaran</h4>
                                <div class="max-h-32 overflow-y-auto space-y-2">
                                    @foreach($availableMethods as $id => $name)
                                    <label class="flex items-start cursor-pointer group">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="methods" value="{{ $id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6 text-gray-700 group-hover:text-gray-900">{{ $name }}</div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @if(!empty($availableMedia))
                            <div class="bg-white p-4 rounded-xl border border-gray-200">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Media Pembelajaran</h4>
                                <div class="max-h-32 overflow-y-auto space-y-2">
                                    @foreach($availableMedia as $id => $name)
                                    <label class="flex items-start cursor-pointer group">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="media" value="{{ $id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6 text-gray-700 group-hover:text-gray-900">{{ $name }}</div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Rencana Penilaian</label>
                            <textarea wire:model="assessment_plan" rows="2" class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"></textarea>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Catatan Khusus</label>
                            <textarea wire:model="notes" rows="2" class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"></textarea>
                        </div>

                    </form>
                </div>

                <div class="border-t border-gray-100 px-4 py-4 sm:px-6 flex justify-end space-x-3 bg-white">
                    <button type="button" @click="open = false" wire:click="$set('isTopicModalOpen', false)" class="rounded-xl bg-gray-50 px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm border border-gray-200 hover:bg-gray-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" form="topicForm" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:bg-primary-700 transition-all active:scale-95 flex items-center justify-center">
                        <span wire:loading.class="hidden" wire:target="saveTopic">Kirim</span>
                        <span wire:loading wire:target="saveTopic" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Kirim...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply to Dates Modal (Slide-over) -->
    <div x-data="{ openApply: @entangle('isApplyModalOpen') }" 
         x-show="openApply" 
         class="fixed inset-0 z-[100] overflow-hidden" 
         style="display: none;">
        
        <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" 
             x-show="openApply"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="openApply = false" wire:click="$set('isApplyModalOpen', false)"></div>
        
        <div class="fixed inset-y-0 right-0 flex max-w-full sm:pl-16">
            <div x-show="openApply"
                 x-transition:enter="transform transition ease-in-out duration-300"
                 x-transition:enter-start="translate-y-full sm:translate-y-0 sm:translate-x-full"
                 x-transition:enter-end="translate-y-0 sm:translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300"
                 x-transition:leave-start="translate-y-0 sm:translate-x-0"
                 x-transition:leave-end="translate-y-full sm:translate-y-0 sm:translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col h-[calc(100%-6rem)] mt-24 sm:h-full sm:mt-0 rounded-t-3xl sm:rounded-none">
                
                <div class="px-4 py-6 bg-white border-b border-gray-100 sm:px-6 flex items-center justify-between rounded-t-3xl sm:rounded-none">
                    <div class="flex items-center space-x-2">
                        <div class="p-2 bg-primary-50 text-primary-600 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900">Apply ke Tanggal</h2>
                    </div>
                    <button @click="openApply = false" wire:click="$set('isApplyModalOpen', false)" class="text-gray-400 hover:text-gray-500 bg-gray-50 p-2 rounded-full">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                
                <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6 bg-gray-50/50">
                    <form id="applyForm" wire:submit.prevent="applyToDates" class="space-y-5">
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Mulai <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="start_date" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Selesai <span class="text-red-500">*</span></label>
                                <input type="date" wire:model="end_date" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Jam Mulai <span class="text-red-500">*</span></label>
                                <input type="time" wire:model="start_time" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Jam Selesai <span class="text-red-500">*</span></label>
                                <input type="time" wire:model="end_time" required class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                            <label class="block text-sm font-medium leading-6 text-gray-900 mb-3">Hari Aktif Mengajar <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-2 gap-3">
                                @php
                                    $days = [1=>'Senin', 2=>'Selasa', 3=>'Rabu', 4=>'Kamis', 5=>'Jumat', 6=>'Sabtu'];
                                @endphp
                                @foreach($days as $val => $label)
                                <label class="flex items-center cursor-pointer group">
                                    <div class="flex h-6 items-center">
                                        <input wire:model="weekdays" value="{{ $val }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                    </div>
                                    <div class="ml-3 text-sm leading-6 text-gray-700 group-hover:text-gray-900">{{ $label }}</div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium leading-6 text-gray-900">Periode / Jam Ke- (Opsional)</label>
                            <input type="text" wire:model="period" placeholder="Misal: Jam ke-1" class="mt-1 block w-full rounded-xl border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                        </div>

                        <div class="bg-white p-4 rounded-xl border border-gray-200 space-y-3">
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm font-medium text-gray-900">Lewati hari libur nasional</span>
                                <input wire:model="skip_holidays" type="checkbox" class="h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                            </label>
                            <label class="flex items-center justify-between cursor-pointer">
                                <span class="text-sm font-medium text-gray-900">Langsung jadikan aktif (Publish)</span>
                                <input wire:model="publish_immediately" type="checkbox" class="h-5 w-5 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                            </label>
                        </div>

                    </form>
                </div>

                <div class="border-t border-gray-100 px-4 py-4 sm:px-6 bg-white">
                    <button type="submit" form="applyForm" class="w-full rounded-xl bg-primary-600 px-6 py-3.5 text-sm font-bold text-white shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:bg-primary-700 transition-all active:scale-95 flex justify-center items-center">
                        <span wire:loading.remove wire:target="applyToDates">Jadwalkan Sesi</span>
                        <span wire:loading wire:target="applyToDates" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div x-show="showNotif" 
         x-transition:enter="transform ease-out duration-300 transition" 
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2" 
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0" 
         x-transition:leave="transition ease-in duration-100" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed bottom-20 left-1/2 transform -translate-x-1/2 z-[110] max-w-sm w-[90%] bg-gray-900 shadow-xl rounded-xl pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
         style="display: none;">
        <div class="p-4">
            <div class="flex items-center">
                <div class="w-0 flex-1 flex justify-between">
                    <p class="w-0 flex-1 text-sm font-medium text-white" x-text="notification"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="showNotif = false" class="bg-gray-900 rounded-md inline-flex text-gray-400 hover:text-white focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
