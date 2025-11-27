<x-layouts.base :ogTitle="$ogTitle">


    <x-sections.preloader />


    <x-sections.guest.header />

    @auth

    <livewire:forms.subscription />

    @endauth

    {{ $slot }}

    <x-sections.guest.footer />

</x-layouts.base>
