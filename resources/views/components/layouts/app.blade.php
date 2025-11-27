<x-layouts.base :title="$title" :description="$description" :ogTitle="$ogTitle" :ogDescription="$ogDescription" :ogImage="$ogImage" :publishedAt="$publishedAt" :updatedAt="$updatedAt" />

<x-sections.preloader />

@livewire('search.web')

<x-sections.side-menu />


<livewire:forms.subscription />


{{ $slot }}

</x-layouts.base>
