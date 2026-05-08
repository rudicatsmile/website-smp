@props(['settings', 'transparent' => false])
@php
    $skin = $settings->active_skin ?? 'education';
    if (!\Illuminate\Support\Facades\View::exists("skins.{$skin}.navbar")) {
        $skin = 'education';
    }
@endphp
@include("skins.{$skin}.navbar", ['settings' => $settings, 'transparent' => $transparent])
