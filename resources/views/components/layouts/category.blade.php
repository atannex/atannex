<x-layouts.app :ogTitle="$ogTitle">

    <x-sections.category.header />

    <x-partials.breadcrumb />


    {{ $slot }}

    <x-sections.category.footer />

</x-layouts.app>
