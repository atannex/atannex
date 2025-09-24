<x-layouts.page :title="seo_title($region->title)">

    @foreach ($region->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.page>
