<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @livewireStyles()

    @include('components.layouts.files.header')

    @include('components.layouts.files.googletagmanager')

</head>
<body>

    {{ $slot }}

    @livewireScripts()

    @include('components.layouts.files.footer')

</body>
</html>
