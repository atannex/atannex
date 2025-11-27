<x-layouts.base :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt">

    <!-- ================================
         PRELOADER
         Shown briefly during initial load.
    ================================ -->
    <x-sections.preloader />

    <!-- ================================
         GLOBAL SEARCH COMPONENT
         Livewire-powered sitewide search.
    ================================ -->
    @livewire('search.web')

    <!-- ================================
         MOBILE & OVERLAY SIDE MENU
         Toggles navigation for smaller screens.
    ================================ -->
    <x-sections.side-menu />

    <!-- ================================
         NEWSLETTER / SUBSCRIPTION FORM
         Livewire-driven for instant UX.
    ================================ -->
    <livewire:forms.subscription />

    <!-- ================================
         CATEGORY HEADER
         Displays category context, title,
         and navigation for category-based pages.
    ================================ -->
    <x-sections.category.header />

    <!-- ================================
         BREADCRUMB NAVIGATION
         Shows page hierarchy for SEO + UX.
    ================================ -->
    <x-partials.breadcrumb />

    <!-- ================================
         MAIN PAGE CONTENT SLOT
         Dynamic content injected by each page.
    ================================ -->
    {{ $slot }}

    <!-- ================================
         CATEGORY FOOTER
         Contains related links, category info,
         or additional section navigation.
    ================================ -->
    <x-sections.category.footer />

</x-layouts.base>
