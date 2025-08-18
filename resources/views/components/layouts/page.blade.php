<x-layouts.base :title="$title">

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />


    <x-sections.subscribe />

    <x-sections.pages.header />



    {{ $slot }}



    <x-sections.pages.footer />

</x-layouts.base>
