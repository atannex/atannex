<x-layouts.base :ogTitle="$ogTitle">

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />


    <livewire:forms.subscription />


    {{ $slot }}

</x-layouts.base>
