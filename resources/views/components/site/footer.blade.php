@props(['settings'])
@php
    $skin = $settings->active_skin ?? 'education';
    if (!\Illuminate\Support\Facades\View::exists("skins.{$skin}.footer")) {
        $skin = 'education';
    }
@endphp
@include("skins.{$skin}.footer", ['settings' => $settings])
