<x-layouts.base :title="$title">

    <x-sections.preloader />

    <x-sections.pages.header />



    {{ $slot }}



    <x-sections.pages.footer />

</x-layouts.base>
