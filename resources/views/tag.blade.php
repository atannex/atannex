<x-layouts.page :title="$seoTitle">

    @include('sections.blog-list', ['posts' => $posts])

</x-layouts.page>
