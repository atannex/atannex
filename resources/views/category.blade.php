<x-layouts.category :title="seo_title($category->name)">

    @foreach ($category->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.category>
