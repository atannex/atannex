<x-layouts.base :title="$title">

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />


    <x-sections.subscribe />


    {{ $slot }}



</x-layouts.base>
