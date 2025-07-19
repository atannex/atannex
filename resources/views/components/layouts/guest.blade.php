<x-layouts.base :title="$title">


    <x-sections.preloader />


    <x-sections.guest.header />


    {{ $slot }}



    <x-sections.guest.footer />


</x-layouts.base>
