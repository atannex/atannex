<x-layouts.base :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt" >


    <x-sections.preloader />


    <x-sections.guest.header />

    @auth

    <livewire:forms.subscription />

    @endauth

    {{ $slot }}

    <x-sections.guest.footer />

</x-layouts.base>
