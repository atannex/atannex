<x-layouts.page :ogTitle="$seoTitle">

    @include('sections.blog-list', ['posts' => $posts])

</x-layouts.page>
