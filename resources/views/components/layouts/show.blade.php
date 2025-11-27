<x-layouts.base :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt">

    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />

    <livewire:forms.subscription />

    <x-sections.category.header />

    <x-partials.breadcrumb />


    {{ $slot }}

    <x-sections.category.footer />

</x-layouts.base>
