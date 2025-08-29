@foreach ($widget->tabs as $tab)
@foreach ($tab['content'] as $post)
<div class="mt-4 col-xl-6 mt-xl-0">
    <div class="dark-theme img-overlay2">
        <div class="blog-style3">
            <div class="blog-img">

                @include('partials.image',['class'=> 'top-stories-main-center'])

            </div>

            <div class="blog-content">

                @include('partials.category')

                <h3 class="box-title-30">

                    @include('partials.title')

                </h3>

                <div class="blog-meta">

                    @include('partials.author')

                    @include('partials.date')
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach
@endforeach
