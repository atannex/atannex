<x-layouts.app :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt" />

<x-sections.category.header />

<x-partials.breadcrumb />


{{ $slot }}

<x-sections.category.footer />

</x-layouts.app>
