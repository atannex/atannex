<x-layouts.base :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt">

    <!-- Preloader -->
    <x-sections.preloader />

    <!-- Global Search -->
    @livewire('search.web')

    <!-- Sidebar Menu -->
    <x-sections.side-menu />

    <!-- Newsletter / Subscription Form -->
    <livewire:forms.subscription />

    <!-- Main Page Content -->
    {{ $slot }}

</x-layouts.base>
