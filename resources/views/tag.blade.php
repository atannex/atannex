<x-layouts.page :title="$seoTitle">

    <x-partials.breadcrumb />

    @include('sections.blog-list', ['posts' => $posts])

</x-layouts.page>
