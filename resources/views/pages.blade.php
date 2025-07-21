<x-layouts.page :title="$page->title . ' - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">

    @foreach ($page->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.page>
