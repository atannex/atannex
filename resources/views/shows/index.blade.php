<x-layouts.category :title="seo_title($module->post->title)">


    <section class="th-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <x-shows.header-content :module="$module" />

                </div>
                <div class="col-xxl-9 col-lg-8">

                    <div class="th-blog blog-single">
                        <div class="blog-content-wrap">
                            <div class="share-links-wrap">

                                <x-shows.social-share :module="$module" :icons="$icons" />

                            </div>
                            <div class="blog-content">

                                <livewire:show.info :post="$module->post" />

                                <x-shows.content :module="$module"/>

                                <x-shows.related-tag :relatedTags="$relatedTags" />

                            </div>
                        </div>
                    </div>

                    <x-shows.navigation :navigation="$navigation" />

                    <x-shows.author :module="$module" :medias="$medias" />

                    <livewire:forms.comment wire:key="comments-{{ $module->post->id }}" :commentable="$module->post" />

                    <x-shows.related-posts :relatedPosts="$relatedPosts" />

                </div>
                <div class="col-xxl-3 col-lg-4 sidebar-wrap">

                    @include('partials.aside')

                </div>
            </div>
        </div>
    </section>
</x-layouts.category>
