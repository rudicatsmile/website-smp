<!DOCTYPE html>
<html><body>
<h2>Pesan Kontak Baru</h2>
<p><strong>Nama:</strong> {{ $message->name }}</p>
<p><strong>Email:</strong> {{ $message->email }}</p>
@if($message->phone)<p><strong>Telp:</strong> {{ $message->phone }}</p>@endif
<p><strong>Subjek:</strong> {{ $message->subject }}</p>
<p><strong>Pesan:</strong></p>
<p>{!! nl2br(e($message->message)) !!}</p>
<hr>
<small>Dikirim pada {{ $message->created_at->translatedFormat('d F Y H:i') }}</small>
</body></html>
