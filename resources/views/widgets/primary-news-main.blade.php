<div class="col-xl-8">
    @foreach($widget->tabs as $tab)
    <h2 class="sec-title has-line">{{ $tab['title'] }}</h2>
    <div class="row gy-4">
        @foreach($tab['content'] as $post)
        <div class="col-sm-6 border-blog two-column">
            <div class="blog-style1">
                <div class="blog-img">

                    @include('partials.image')

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
    @endforeach
</div>
