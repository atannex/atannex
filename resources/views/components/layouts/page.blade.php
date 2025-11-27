<x-layouts.base :title="$title ?? __('Atannex - Lebialem Community News')" :description="$description ?? __('Lebialem news and community updates from Atannex')" :ogTitle="$ogTitle ?? null" :ogDescription="$ogDescription ?? null" :ogImage="$ogImage ?? null" :publishedAt="$publishedAt ?? null" :updatedAt="$updatedAt ?? null">
    <x-sections.preloader />

    @livewire('search.web')

    <x-sections.side-menu />

    <livewire:forms.subscription />

    <x-sections.pages.header />

    {{ $slot }}

    <x-sections.pages.footer />
</x-layouts.base>
