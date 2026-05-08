@props([
    'key' => null,
    'title' => null,
    'subtitle' => null,
    'breadcrumbs' => [],
    'icon' => null,
])
@php
    $skin = app(\App\Settings\GeneralSettings::class)->active_skin ?: 'education';
    if (!\Illuminate\Support\Facades\View::exists("skins.{$skin}.page-hero")) {
        $skin = 'education';
    }
@endphp
@include("skins.{$skin}.page-hero", [
    'key' => $key,
    'title' => $title,
    'subtitle' => $subtitle,
    'breadcrumbs' => $breadcrumbs,
    'icon' => $icon,
])
