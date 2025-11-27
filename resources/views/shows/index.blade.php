<x-layouts.show :title="seo_title($module->post->title)" :description="$module->post->description" :ogTitle="$module->post->title" :ogDescription="$module->post->description" :ogImage="asset('storage/' . $module->post->image)" :publishedAt="$module->post->published_at" :updatedAt="$module->post->updated_at">

    <!-- ============================================================
         SINGLE POST PAGE WRAPPER
         Handles the layout for detailed article views.
    ============================================================ -->
    <section class="th-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">

                <!-- =========================
                     ARTICLE HEADER (Title, meta)
                ========================== -->
                <div class="col-12">
                    <x-shows.header-content :module="$module" />
                </div>

                <!-- ============================================================
                     MAIN ARTICLE CONTENT
                     Includes share buttons, post body, tags, navigation,
                     author section, comments, and related posts.
                ============================================================ -->
                <div class="col-xxl-9 col-lg-8">
                    <div class="th-blog blog-single">
                        <div class="blog-content-wrap">

                            <!-- Social share icons (Facebook, Twitter, etc.) -->
                            <div class="share-links-wrap">
                                <x-shows.social-share :module="$module" :icons="$icons" />
                            </div>

                            <div class="blog-content">

                                <!-- Post metadata (views, timestamp, category, etc.) -->
                                <livewire:show.info :post="$module->post" />

                                <!-- Main article content body -->
                                <x-shows.content :module="$module" />

                                <!-- Tags associated with this post -->
                                <x-shows.related-tag :relatedTags="$relatedTags" />

                            </div>
                        </div>
                    </div>

                    <!-- Previous / Next article navigation -->
                    <x-shows.navigation :navigation="$navigation" />

                    <!-- Author biography / media section -->
                    <x-shows.author :module="$module" :medias="$medias" />

                    <!-- Comments section (Livewire-powered) -->
                    <livewire:forms.comment wire:key="comments-{{ $module->post->id }}" :commentable="$module->post" />

                    <!-- Related posts suggestions -->
                    <x-shows.related-posts :relatedPosts="$relatedPosts" />
                </div>

                <!-- ============================================================
                     SIDEBAR
                     Contains widgets such as categories, trending posts, ads, etc.
                ============================================================ -->
                <div class="col-xxl-3 col-lg-4 sidebar-wrap">
                    @include('partials.aside')
                </div>

            </div>
        </div>
    </section>

</x-layouts.show>
