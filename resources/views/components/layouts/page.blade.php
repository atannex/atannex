<x-layouts.base :ogTitle="$ogTitle">

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />


    <livewire:forms.subscription />

    <x-sections.pages.header />

    {{ $slot }}

    <x-sections.pages.footer />

</x-layouts.base>
