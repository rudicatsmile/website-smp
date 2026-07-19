<div class="p-6 pb-24 relative min-h-screen">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('mobile.dashboard') }}" wire:navigate class="mr-4 text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-900">Rencana Pembelajaran</h1>
        </div>
        <button wire:click="openCreateModal" class="bg-primary-600 text-white p-2 rounded-full shadow-sm hover:bg-primary-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
        </button>
    </div>

    <!-- List -->
    @if($plans->count() > 0)
        <div class="space-y-4 mb-8">
            @foreach($plans as $plan)
                <div class="block bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex flex-col active:scale-[0.98] transition-transform duration-200">
                    <div class="flex justify-between items-start mb-2">
                        <a href="{{ route('mobile.plans.show', $plan->id) }}" wire:navigate class="flex-1 block cursor-pointer">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-50 text-orange-700 mb-2">
                                {{ $plan->schoolClass->name ?? 'Semua Kelas' }}
                            </span>
                            <h3 class="text-base font-bold text-gray-900">{{ $plan->title }}</h3>
                            <p class="text-sm text-gray-600 line-clamp-1">{{ $plan->subject->name ?? 'Mata Pelajaran Tidak Ditemukan' }}</p>
                        </a>
                        
                        <!-- Actions Dropdown / Icons -->
                        <div class="flex flex-col items-end space-y-2 ml-2 relative z-10">
                            @if($plan->is_active)
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-green-100 text-green-800 text-center">Aktif</span>
                            @else
                                <span class="inline-block px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-800 text-center">Tdk Aktif</span>
                            @endif
                            <div class="flex space-x-2 mt-2">
                                <button type="button" wire:click.prevent="openEditModal({{ $plan->id }})" class="p-1.5 bg-blue-50 text-blue-600 rounded-md hover:bg-blue-100 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button type="button" wire:click.prevent="delete({{ $plan->id }})" wire:confirm="Yakin ingin menghapus rencana ini?" class="p-1.5 bg-red-50 text-red-600 rounded-md hover:bg-red-100 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <a href="{{ route('mobile.plans.show', $plan->id) }}" wire:navigate class="flex items-center justify-between text-xs text-gray-500 mt-3 pt-3 border-t border-gray-50 cursor-pointer block">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $plan->time_allocation ?? '-' }}</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>SMt {{ $plan->semester }} ({{ $plan->academic_year }})</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        
        @if(method_exists($plans, 'links'))
            <div class="mt-4 pb-4">
                {{ $plans->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12 bg-white rounded-2xl border border-gray-100 shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 text-orange-600 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Belum Ada Rencana</h3>
            <p class="mt-1 text-sm text-gray-500">Anda belum membuat rencana pembelajaran.</p>
            <button wire:click="openCreateModal" class="mt-4 inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700">
                Buat Rencana Baru
            </button>
        </div>
    @endif

    <!-- Slide-over Modal -->
    <div x-data="{ open: @entangle('isModalOpen') }" 
         x-show="open" 
         class="fixed inset-0 z-[100] overflow-hidden" 
         style="display: none;"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-gray-500/50 backdrop-blur-sm transition-opacity" @click="open = false" wire:click="closeModal"></div>
        
        <!-- Modal Panel -->
        <div class="fixed inset-y-0 right-0 flex max-w-full sm:pl-16">
            <div x-show="open"
                 x-transition:enter="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in-out duration-300 sm:duration-500"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full"
                 class="w-screen max-w-md bg-white shadow-2xl flex flex-col h-full">
                <!-- Header -->
                <div class="px-4 py-6 bg-primary-700 sm:px-6 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">
                        {{ $modalMode === 'create' ? 'Buat Rencana Baru' : 'Edit Rencana' }}
                    </h2>
                    <button @click="open = false" wire:click="closeModal" class="text-primary-200 hover:text-white">
                        <span class="sr-only">Close panel</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                    
                    <!-- Form Body -->
                    <div class="flex-1 overflow-y-auto px-4 py-6 sm:px-6">
                        <form id="planForm" wire:submit="save" class="space-y-5">
                            
                            <!-- Topik -->
                            <div>
                                <label class="block text-sm font-medium leading-6 text-gray-900">Judul / Topik <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="title" required class="mt-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                @error('title') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                            </div>

                            <!-- Kelas & Mapel -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Kelas <span class="text-red-500">*</span></label>
                                    <select wire:model="school_class_id" required class="mt-2 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6">
                                        <option value="">-- Pilih --</option>
                                        @foreach($classes as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('school_class_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Mapel <span class="text-red-500">*</span></label>
                                    <select wire:model.live="material_category_id" required class="mt-2 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6">
                                        <option value="">-- Pilih --</option>
                                        @foreach($subjects as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    @error('material_category_id') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Tahun & Semester -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Tahun Ajaran <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="academic_year" placeholder="2025/2026" required class="mt-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    @error('academic_year') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Semester <span class="text-red-500">*</span></label>
                                    <select wire:model="semester" required class="mt-2 block w-full rounded-md border-0 py-2 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-primary-600 sm:text-sm sm:leading-6">
                                        <option value="ganjil">Ganjil</option>
                                        <option value="genap">Genap</option>
                                    </select>
                                    @error('semester') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Waktu & Status -->
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Alokasi Waktu</label>
                                    <input type="text" wire:model="time_allocation" placeholder="2 x 40 menit" class="mt-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    @error('time_allocation') <span class="text-sm text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Status</label>
                                    <div class="mt-4 flex items-center">
                                        <input type="checkbox" wire:model="is_active" id="is_active" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        <label for="is_active" class="ml-2 text-sm text-gray-900">Aktif Digunakan</label>
                                    </div>
                                </div>
                            </div>
                            
                            @if($material_category_id)
                            <div class="border-t border-gray-200 pt-5 mt-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Tujuan Pembelajaran</h4>
                                <div class="max-h-40 overflow-y-auto space-y-2 border border-gray-200 rounded-md p-3 bg-gray-50">
                                    @forelse($objectives as $id => $name)
                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="learning_objective_ids" value="{{ $id }}" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label class="font-medium text-gray-900">{{ $name }}</label>
                                        </div>
                                    </div>
                                    @empty
                                    <div class="text-sm text-gray-500 italic">Belum ada tujuan pembelajaran untuk mapel ini.</div>
                                    @endforelse
                                </div>
                            </div>
                            @endif

                            <div class="border-t border-gray-200 pt-5 mt-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Model Pembelajaran</h4>
                                <div class="max-h-40 overflow-y-auto space-y-2 border border-gray-200 rounded-md p-3 bg-gray-50">
                                    @foreach($models as $id => $name)
                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="learning_model_ids" value="{{ $id }}" type="checkbox" id="model_{{ $id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="model_{{ $id }}" class="font-medium text-gray-900">{{ $name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-5 mt-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Metode Pembelajaran</h4>
                                <div class="max-h-40 overflow-y-auto space-y-2 border border-gray-200 rounded-md p-3 bg-gray-50">
                                    @foreach($methods as $id => $name)
                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model="default_methods" value="{{ $id }}" type="checkbox" id="method_{{ $id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="method_{{ $id }}" class="font-medium text-gray-900">{{ $name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="border-t border-gray-200 pt-5 mt-5">
                                <h4 class="text-sm font-semibold text-gray-900 mb-3">Media Pembelajaran</h4>
                                <div class="max-h-40 overflow-y-auto space-y-2 border border-gray-200 rounded-md p-3 bg-gray-50">
                                    @foreach($medias as $id => $name)
                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model.live="default_media" value="{{ $id }}" type="checkbox" id="media_{{ $id }}" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="media_{{ $id }}" class="font-medium text-gray-900">{{ $name }}</label>
                                        </div>
                                    </div>
                                    @endforeach
                                    <!-- Opsi Lainnya -->
                                    <div class="flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input wire:model.live="default_media" value="lainnya" type="checkbox" id="media_lainnya" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="media_lainnya" class="font-medium text-gray-900">— Lainnya</label>
                                        </div>
                                    </div>
                                </div>
                                
                                @if(in_array('lainnya', (array)$default_media))
                                <div class="mt-3">
                                    <label class="block text-sm font-medium leading-6 text-gray-900">Sebutkan Media Lainnya</label>
                                    <input type="text" wire:model="default_media_other" placeholder="Tulis media pembelajaran lainnya..." class="mt-2 block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                </div>
                                @endif
                            </div>

                        </form>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="border-t border-gray-200 px-4 py-4 sm:px-6 flex justify-end space-x-3 bg-gray-50">
                        <button type="button" wire:click="closeModal" class="rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" form="planForm" class="rounded-xl bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-[0_4px_14px_0_rgba(79,70,229,0.39)] hover:bg-primary-700 transition-all active:scale-95 flex items-center justify-center">
                        <span wire:loading.class="hidden" wire:target="save">Kirim</span>
                        <span wire:loading wire:target="save" class="hidden">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Kirim...
                        </span>
                    </button>
                </div>
            </div>
        </div>
</div>
