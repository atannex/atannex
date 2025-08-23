<x-layouts.page :title="$seoTitle">

    <x-partials.breadcrumb />

    @include('sections.category-3-column', ['posts' => $posts])

</x-layouts.page>
