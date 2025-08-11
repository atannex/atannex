<x-layouts.category :title="__('Top Stories, Breaking News & Headlines') . ' | ' . config('app.name')">

    <section class="th-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <x-shows.header-content />

                </div>
                <div class="col-xxl-9 col-lg-8">

                    <div class="th-blog blog-single">
                        <div class="blog-content-wrap">
                            <div class="share-links-wrap">

                                <x-shows.share-links />

                            </div>
                            <div class="blog-content">

                                <x-shows.info />

                                <x-shows.content />

                                <x-shows.related-tag />

                            </div>
                        </div>
                    </div>

                    <x-shows.navigation />

                    <x-shows.author />

                    @livewire('forms.comment')

                    <x-shows.related-posts />

                </div>
                <div class="col-xxl-3 col-lg-4 sidebar-wrap">

                    <x-shows.aside />

                </div>
            </div>
        </div>
    </section>
</x-layouts.category>
