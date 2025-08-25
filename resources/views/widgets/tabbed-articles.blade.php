<div class="nav tab-menu indicator-active" role="tablist">
    @foreach ($widget->tabs as $index => $tab)
    <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" id="top-tabs-{{ $index }}-button" data-bs-toggle="tab" data-bs-target="#top-tabs-{{ $index }}" type="button" role="tab" aria-controls="top-tabs-{{ $index }}" aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
        {{ $tab['title'] }}
    </button>
    @endforeach
</div>

<div class="tab-content">
    @foreach ($widget->tabs as $index => $tab)
    <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" id="top-tabs-{{ $index }}" role="tabpanel" aria-labelledby="top-tabs-{{ $index }}-button">
        <div class="row gy-4">
            @foreach ($tab['content'] as $post)
            <div class="col-xl-12 col-md-6 border-blog">
                <article class="blog-style2">
                    <div class="blog-img">

                        @include('partials.image')

                    </div>
                    <div class="blog-content">

                        @include('partials.category')

                        <h3 class="box-title-18">

                            @include('partials.title')

                        </h3>
                        <div class="blog-meta">

                            @include('partials.date')
                        </div>
                    </div>
                </article>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>
