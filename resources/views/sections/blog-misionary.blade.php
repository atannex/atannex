<section class="space-top space-extra-bottom">
    <div class="container">
        <div class="row">
            <div class="col-xxl-9 col-lg-8">
                <div class="row gy-30 filter-active">
                    @foreach($posts as $post)
                    <div class="filter-item col-xl-4 col-sm-6">
                        <div class="blog-style1">
                            <div class="blog-img">

                                @include('partials.image',['class'=> 'small-image-carousel'])

                                @include('partials.category')

                            </div>
                            <h3 class="box-title-24">

                                @include('partials.title')

                            </h3>
                            <div class="blog-meta">

                                @include('partials.author')

                                @include('partials.date')

                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="col-xxl-3 col-lg-4 sidebar-wrap">

                <aside class="sidebar-area">
                    <div class="widget widget_tag_cloud">

                        @livewire('search.post')

                    </div>

                    @include('partials.aside.category')

                    @include('partials.aside.recent-posts')

                    @include('partials.aside.tag')

                </aside>

            </div>
        </div>
    </div>
</section>
