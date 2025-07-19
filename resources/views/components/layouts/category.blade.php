<x-layouts.app :title="$title">

    <x-sections.category.header />

    <x-partials.breadcrumb />


    {{ $slot }}



    <x-sections.category.footer />



</x-layouts.app>
