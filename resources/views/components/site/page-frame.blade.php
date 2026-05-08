@props(['padded' => true])
@php
    $skin = app(\App\Settings\GeneralSettings::class)->active_skin ?: 'education';
    if (!\Illuminate\Support\Facades\View::exists("skins.{$skin}.page-frame")) {
        $skin = 'education';
    }
@endphp
@include("skins.{$skin}.page-frame", [
    'padded' => $padded,
    'frameSlot' => $slot,
    'frameAttributes' => $attributes,
])
