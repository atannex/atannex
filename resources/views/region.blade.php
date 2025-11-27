<x-layouts.page :ogTitle="$seoTitle">

    @foreach ($region->sections as $section)

    @include("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.page>
