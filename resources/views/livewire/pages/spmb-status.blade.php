<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-3xl font-bold text-slate-900">Cek Status Pendaftaran SPMB</h1>
    <form wire:submit="check" class="mt-6 bg-white rounded-xl p-6 shadow-sm space-y-4">
        <div>
            <label class="text-sm font-medium">Nomor Pendaftaran</label>
            <input type="text" wire:model="registration_number" placeholder="SPMB-YYYYMMDD-XXXX" class="mt-1 w-full border rounded-lg px-3 py-2">
            @error('registration_number')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <div>
            <label class="text-sm font-medium">Tanggal Lahir</label>
            <input type="date" wire:model="birth_date" class="mt-1 w-full border rounded-lg px-3 py-2">
            @error('birth_date')<div class="text-red-600 text-xs mt-1">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-semibold">Cek Status</button>
    </form>

    @if($error)
        <div class="mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg">{{ $error }}</div>
    @endif

    @if($found)
        @php
            $labels = ['pending' => 'Menunggu Verifikasi', 'verifying' => 'Sedang Diverifikasi', 'accepted' => 'Diterima', 'rejected' => 'Tidak Diterima', 'waiting_list' => 'Daftar Tunggu'];
            $colors = ['pending' => 'bg-slate-100 text-slate-700', 'verifying' => 'bg-blue-100 text-blue-700', 'accepted' => 'bg-emerald-100 text-emerald-700', 'rejected' => 'bg-red-100 text-red-700', 'waiting_list' => 'bg-amber-100 text-amber-700'];
        @endphp
        <div class="mt-6 bg-white rounded-xl p-6 shadow-sm">
            <h2 class="text-xl font-bold text-slate-900">{{ $found->full_name }}</h2>
            <div class="text-sm text-slate-600">{{ $found->registration_number }} · {{ $found->period?->name }}</div>
            <div class="mt-4">
                <span class="inline-block px-4 py-2 rounded-full font-semibold {{ $colors[$found->status] ?? '' }}">{{ $labels[$found->status] ?? $found->status }}</span>
            </div>
            @if($found->admin_note)
                <div class="mt-4 p-3 bg-slate-50 rounded-lg text-sm">
                    <strong>Catatan Panitia:</strong> {{ $found->admin_note }}
                </div>
            @endif
        </div>
    @endif
</div>
