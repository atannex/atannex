<x-layouts.page :title="$seo_title($page->title)">

    @foreach ($page->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.page>
