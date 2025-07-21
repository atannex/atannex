<x-layouts.category :title="$category->name . ' - ' . __('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">

    @foreach ($category->sections as $section)

    @includeIf("sections.{$section->slug}", ['section' => $section])

    @endforeach

</x-layouts.category>
