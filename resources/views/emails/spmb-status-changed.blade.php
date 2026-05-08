<!DOCTYPE html>
<html><body>
@php
    $labels = ['pending' => 'Menunggu', 'verifying' => 'Sedang Diverifikasi', 'accepted' => 'DITERIMA', 'rejected' => 'Tidak Diterima', 'waiting_list' => 'Daftar Tunggu'];
@endphp
<h2>Update Status Pendaftaran SPMB</h2>
<p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
<p>Status pendaftaran Anda telah diperbarui:</p>
<ul>
    <li><strong>Nomor Pendaftaran:</strong> {{ $registration->registration_number }}</li>
    <li><strong>Status Sekarang:</strong> {{ $labels[$registration->status] ?? $registration->status }}</li>
</ul>
@if($registration->admin_note)
    <p><strong>Catatan Panitia:</strong> {{ $registration->admin_note }}</p>
@endif
<p>Salam,<br>Panitia SPMB SMP Al Wahoniyah 9</p>
</body></html>
