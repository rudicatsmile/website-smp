<div class="max-w-3xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Pendaftaran SPMB</h1>
    @if($period)<p class="text-slate-600 mt-1">{{ $period->name }}</p>@endif

    {{-- Stepper --}}
    @if($step < 4)
    <div class="flex items-center gap-2 mt-6">
        @foreach(['Data Diri','Orang Tua & Sekolah','Dokumen'] as $i => $label)
            @php($n = $i + 1)
            <div class="flex-1 flex items-center gap-2">
                <div class="w-8 h-8 rounded-full grid place-items-center text-sm font-bold {{ $step >= $n ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600' }}">{{ $n }}</div>
                <span class="text-sm {{ $step === $n ? 'font-semibold text-slate-900' : 'text-slate-500' }}">{{ $label }}</span>
            </div>
        @endforeach
    </div>
    @endif

    <form wire:submit.prevent="{{ $step === 3 ? 'submit' : 'next' }}" class="mt-8 bg-white rounded-xl p-6 shadow-sm">
        @if($step === 1)
            <h2 class="text-xl font-semibold mb-4">Data Diri Calon Siswa</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2"><label class="text-sm font-medium">Nama Lengkap *</label><input type="text" wire:model="full_name" class="mt-1 w-full border rounded-lg px-3 py-2">@error('full_name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">Nama Panggilan</label><input type="text" wire:model="nick_name" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">Jenis Kelamin *</label><select wire:model="gender" class="mt-1 w-full border rounded-lg px-3 py-2"><option value="L">Laki-laki</option><option value="P">Perempuan</option></select></div>
                <div><label class="text-sm">Tempat Lahir *</label><input type="text" wire:model="birth_place" class="mt-1 w-full border rounded-lg px-3 py-2">@error('birth_place')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">Tanggal Lahir *</label><input type="date" wire:model="birth_date" class="mt-1 w-full border rounded-lg px-3 py-2">@error('birth_date')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">NIK</label><input type="text" wire:model="nik" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">NISN</label><input type="text" wire:model="nisn" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">Agama *</label><input type="text" wire:model="religion" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div class="sm:col-span-2"><label class="text-sm">Alamat *</label><textarea wire:model="address" rows="3" class="mt-1 w-full border rounded-lg px-3 py-2"></textarea>@error('address')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">No. HP</label><input type="text" wire:model="phone" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">Email</label><input type="email" wire:model="email" class="mt-1 w-full border rounded-lg px-3 py-2">@error('email')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
            </div>
        @elseif($step === 2)
            <h2 class="text-xl font-semibold mb-4">Data Orang Tua & Asal Sekolah</h2>
            <div class="grid sm:grid-cols-2 gap-4">
                <div><label class="text-sm">Nama Ayah *</label><input type="text" wire:model="father_name" class="mt-1 w-full border rounded-lg px-3 py-2">@error('father_name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">Pekerjaan Ayah</label><input type="text" wire:model="father_job" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">No. HP Ayah</label><input type="text" wire:model="father_phone" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">Nama Ibu *</label><input type="text" wire:model="mother_name" class="mt-1 w-full border rounded-lg px-3 py-2">@error('mother_name')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">Pekerjaan Ibu</label><input type="text" wire:model="mother_job" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">No. HP Ibu</label><input type="text" wire:model="mother_phone" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div class="sm:col-span-2"><label class="text-sm">Nama Wali (jika ada)</label><input type="text" wire:model="guardian_name" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
                <div><label class="text-sm">Asal Sekolah *</label><input type="text" wire:model="previous_school" class="mt-1 w-full border rounded-lg px-3 py-2">@error('previous_school')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">Tahun Lulus *</label><input type="text" wire:model="graduation_year" class="mt-1 w-full border rounded-lg px-3 py-2">@error('graduation_year')<div class="text-red-600 text-xs">{{ $message }}</div>@enderror</div>
                <div><label class="text-sm">NPSN</label><input type="text" wire:model="npsn" class="mt-1 w-full border rounded-lg px-3 py-2"></div>
            </div>
        @elseif($step === 3)
            <h2 class="text-xl font-semibold mb-4">Upload Dokumen</h2>
            <p class="text-sm text-slate-500 mb-4">Format PDF/JPG/PNG, maks. 5 MB per file (foto maks. 2 MB).</p>
            <div class="space-y-4">
                @foreach([['kk','Kartu Keluarga (KK)'],['akta','Akta Kelahiran'],['foto','Pas Foto'],['ijazah','Ijazah / SKL'],['raport','Rapor SD/MI']] as $d)
                    <div>
                        <label class="text-sm font-medium">{{ $d[1] }}</label>
                        <input type="file" wire:model="doc_{{ $d[0] }}" class="mt-1 w-full text-sm">
                        @error('doc_'.$d[0])<div class="text-red-600 text-xs">{{ $message }}</div>@enderror
                        <div wire:loading wire:target="doc_{{ $d[0] }}" class="text-xs text-slate-500">Mengupload...</div>
                    </div>
                @endforeach
            </div>
        @elseif($step === 4)
            <div class="text-center py-6">
                <div class="text-emerald-600 text-5xl">✓</div>
                <h2 class="text-2xl font-bold text-slate-900 mt-2">Pendaftaran Berhasil!</h2>
                @if($created)
                    <p class="mt-2 text-slate-600">Nomor Pendaftaran Anda:</p>
                    <div class="text-2xl font-bold text-emerald-700 mt-1">{{ $created->registration_number }}</div>
                    <p class="mt-4 text-sm text-slate-600">Simpan nomor ini untuk cek status pendaftaran. Email konfirmasi telah dikirim (jika alamat email valid).</p>
                @endif
                <a href="{{ route('spmb.status') }}" class="inline-block mt-6 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-semibold">Cek Status Pendaftaran</a>
            </div>
        @endif

        @if($step < 4)
            <div class="flex justify-between mt-6">
                @if($step > 1)
                    <button type="button" wire:click="back" class="px-5 py-2 rounded-lg border border-slate-300 hover:bg-slate-100">Kembali</button>
                @else
                    <span></span>
                @endif
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold">
                    {{ $step === 3 ? 'Submit Pendaftaran' : 'Lanjut' }}
                </button>
            </div>
        @endif
    </form>
</div>
{{-- Care about people's approval and you will be their prisoner. --}}
