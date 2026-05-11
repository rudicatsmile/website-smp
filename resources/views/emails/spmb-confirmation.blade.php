<!DOCTYPE html>
<html><body>
<h2>Konfirmasi Pendaftaran SPMB</h2>
<p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
<p>Terima kasih, pendaftaran Anda telah kami terima dengan detail berikut:</p>
<ul>
    <li><strong>Nomor Pendaftaran:</strong> {{ $registration->registration_number }}</li>
    <li><strong>Periode:</strong> {{ $registration->period?->name }}</li>
    <li><strong>Status:</strong> {{ ucfirst($registration->status) }}</li>
</ul>
<p>Simpan nomor pendaftaran ini untuk pengecekan status melalui website kami.</p>
<p>Salam,<br>Panitia SPMB SMP Al Wathoniyah 9</p>
</body></html>
