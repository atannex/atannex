<x-layouts.category :ogTitle="seo_title($category->name)">

    @foreach ($category->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.category>
