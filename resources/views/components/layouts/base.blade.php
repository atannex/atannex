<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <x-layouts.files.header :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt" />

    @livewireStyles

    <x-layouts.files.googletagmanager />

    @stack('styles')

</head>

<body>

    {{ $slot }}

    @livewireScripts

    <x-layouts.files.footer />

    @stack('scripts')

</body>

</html>
